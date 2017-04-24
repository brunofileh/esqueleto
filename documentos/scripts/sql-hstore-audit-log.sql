--
--	https://wiki.postgresql.org/wiki/Audit_trigger_91plus	
--

CREATE EXTENSION IF NOT EXISTS hstore;

DROP SCHEMA IF EXISTS audit CASCADE;
CREATE SCHEMA audit;
--REVOKE ALL ON SCHEMA audit FROM public;

COMMENT ON SCHEMA audit IS 'Sistema de auditoria baseado nas funções hstore do postgres';


CREATE TABLE audit.log (
	event_id bigserial primary key,
	table_name text not null,
	relid oid not null,
	session_user_name text,
	"timestamp" TIMESTAMP WITH TIME ZONE NOT NULL,
	transaction_id bigint,
	client_addr varchar (50),
	client_query text,
	action TEXT NOT NULL CHECK (action IN ('I','D','U', 'T')),
	row_data hstore,
	changed_fields hstore,
	statement_only boolean not null
);

--REVOKE ALL ON audit.log FROM public;
COMMENT ON TABLE audit.log IS 'Tabela de log genérica';
COMMENT ON COLUMN audit.log.event_id IS 'Unique identifier for each auditable event';
COMMENT ON COLUMN audit.log.table_name IS 'Non-schema-qualified table name of table event occured in';
COMMENT ON COLUMN audit.log.relid IS 'Table OID. Changes with drop/create. Get with ''tablename''::regclass';
COMMENT ON COLUMN audit.log.session_user_name IS 'Login / session user whose statement caused the audited event';
COMMENT ON COLUMN audit.log."timestamp" IS 'Transaction start timestamp for tx in which audited event occurred';
COMMENT ON COLUMN audit.log.transaction_id IS 'Identifier of transaction that made the change. May wrap, but unique paired with action_tstamp_tx.';
COMMENT ON COLUMN audit.log.client_addr IS 'IP address of client that issued query. Null for unix domain socket.';
COMMENT ON COLUMN audit.log.client_query IS 'Top-level query that caused this auditable event. May be more than one statement.';
COMMENT ON COLUMN audit.log.action IS 'Action type; I = insert, D = delete, U = update, T = truncate';
COMMENT ON COLUMN audit.log.row_data IS 'Record value. Null for statement-level trigger. For INSERT this is the new tuple. For DELETE and UPDATE it is the old tuple.';
COMMENT ON COLUMN audit.log.changed_fields IS 'New values of fields changed by UPDATE. Null except for row-level UPDATE events.';
COMMENT ON COLUMN audit.log.statement_only IS '''t'' if audit event is from an FOR EACH STATEMENT trigger, ''f'' for FOR EACH ROW';

CREATE INDEX log_relid_idx ON audit.log(relid);
CREATE INDEX log_timestamp_idx ON audit.log("timestamp");
CREATE INDEX log_action_idx ON audit.log(action);




CREATE TABLE audit.drenagem (
	event_id bigserial primary key,
	table_name text not null,
	relid oid not null,
	session_user_name text,
	"timestamp" TIMESTAMP WITH TIME ZONE NOT NULL,
	transaction_id bigint,
	client_addr varchar (50),
	client_query text,
	action TEXT NOT NULL CHECK (action IN ('I','D','U', 'T')),
	row_data hstore,
	changed_fields hstore,
	statement_only boolean not null
);

--REVOKE ALL ON audit.drenagem FROM public;
CREATE INDEX drenagem_relid_idx ON audit.drenagem(relid);
CREATE INDEX drenagem_timestamp_idx ON audit.drenagem("timestamp");
CREATE INDEX drenagem_action_idx ON audit.drenagem(action);

CREATE TABLE audit.dicionario (
	event_id bigserial primary key,
	table_name text not null,
	relid oid not null,
	session_user_name text,
	"timestamp" TIMESTAMP WITH TIME ZONE NOT NULL,
	transaction_id bigint,
	client_addr varchar (50),
	client_query text,
	action TEXT NOT NULL CHECK (action IN ('I','D','U', 'T')),
	row_data hstore,
	changed_fields hstore,
	statement_only boolean not null
);

--REVOKE ALL ON audit.dicionario FROM public;
CREATE INDEX dicionario_relid_idx ON audit.dicionario(relid);
CREATE INDEX dicionario_timestamp_idx ON audit.dicionario("timestamp");
CREATE INDEX dicionario_action_idx ON audit.dicionario(action);

CREATE TABLE audit._public (
	event_id bigserial primary key,
	table_name text not null,
	relid oid not null,
	session_user_name text,
	"timestamp" TIMESTAMP WITH TIME ZONE NOT NULL,
	transaction_id bigint,
	client_addr varchar (50),
	client_query text,
	action TEXT NOT NULL CHECK (action IN ('I','D','U', 'T')),
	row_data hstore,
	changed_fields hstore,
	statement_only boolean not null
);

--REVOKE ALL ON audit.public FROM public;
CREATE INDEX public_relid_idx ON audit._public(relid);
CREATE INDEX public_timestamp_idx ON audit._public("timestamp");
CREATE INDEX public_action_idx ON audit._public(action);


CREATE TABLE audit.acesso (
	event_id bigserial primary key,
	table_name text not null,
	relid oid not null,
	session_user_name text,
	"timestamp" TIMESTAMP WITH TIME ZONE NOT NULL,
	transaction_id bigint,
	client_addr varchar (50),
	client_query text,
	action TEXT NOT NULL CHECK (action IN ('I','D','U', 'T')),
	row_data hstore,
	changed_fields hstore,
	statement_only boolean not null
);

--REVOKE ALL ON audit.acesso FROM public;
CREATE INDEX acesso_relid_idx ON audit.acesso(relid);
CREATE INDEX acesso_timestamp_idx ON audit.acesso("timestamp");
CREATE INDEX acesso_action_idx ON audit.acesso(action);


CREATE OR REPLACE FUNCTION audit.if_modified_func(
-- TG_ARGV[0] = source_table regclass, 
-- TG_ARGV[1] = target_table regclass, 
-- TG_ARGV[2] = audit_rows boolean, 
-- TG_ARGV[3] = audit_query_text boolean, 
-- TG_ARGV[4] = ignored_cols text[]
) RETURNS TRIGGER AS $body$
DECLARE
	log_table regclass = TG_ARGV[1]::regclass;
	audit_row audit.log;
	include_values boolean;
	log_diffs boolean;
	h_old hstore;
	h_new hstore;
	excluded_cols text[] = ARRAY[]::text[];

	-- client_addr | garante que não ultrapasse o limite do campo (50 chars) caso a var. venha de algum cliente desconhecido
	client_addr varchar (50) = SUBSTRING(current_setting('application_name') FROM 1 FOR 50);
BEGIN
	IF TG_WHEN <> 'AFTER' THEN
		RAISE EXCEPTION 'audit.if_modified_func() may only run as an AFTER trigger';
	END IF;

	IF log_table::text NOT IN ('audit.log', 'audit._public', 'audit.drenagem', 'audit.acesso', 'audit.dicionario') THEN
		RAISE EXCEPTION 'Problema na especificação da tabela de gravação de log. Tabela não existe: %', log_table::text;
	END IF;

	-- Usuário usando cliente PGADMIN, pega o IP da máquina local
	IF client_addr ilike '%pgAdmin%' THEN
		client_addr = inet_client_addr();
	END IF;
	
	audit_row = ROW(
		nextval('audit.log_event_id_seq'),				   -- event_id
		TG_TABLE_SCHEMA::text || '.' || TG_TABLE_NAME::text, -- table_name
		TG_RELID,											-- relation OID for much quicker searches
		session_user::text,								  -- session_user_name
		current_timestamp,								   -- action_tstamp_tx
		txid_current(),									  -- transaction ID
		client_addr,										 -- client addr (IP)
		current_query(),									 -- top-level query or queries (if multistatement) from client
		substring(TG_OP,1,1),								-- action
		NULL, NULL,										  -- row_data, changed_fields
		'f'												  -- statement_only
	);

	IF TG_ARGV[4] IS NOT NULL THEN
		excluded_cols = TG_ARGV[4]::text[];
	END IF;
	
	IF (TG_OP = 'UPDATE' AND TG_LEVEL = 'ROW') THEN
		audit_row.row_data = hstore(OLD.*);
		audit_row.changed_fields =  (hstore(NEW.*) - audit_row.row_data) - excluded_cols;
		IF audit_row.changed_fields = hstore('') THEN
			-- All changed fields are ignored. Skip this update.
			RETURN NULL;
		END IF;
	ELSIF (TG_OP = 'DELETE' AND TG_LEVEL = 'ROW') THEN
		audit_row.row_data = hstore(OLD.*) - excluded_cols;
	ELSIF (TG_OP = 'INSERT' AND TG_LEVEL = 'ROW') THEN
		audit_row.row_data = hstore(NEW.*) - excluded_cols;
	ELSIF (TG_LEVEL = 'STATEMENT' AND TG_OP IN ('INSERT','UPDATE','DELETE','TRUNCATE')) THEN
		audit_row.statement_only = 't';
	ELSE
		RAISE EXCEPTION '[audit.if_modified_func] - Trigger func added as trigger for unhandled case: %, %',TG_OP, TG_LEVEL;
		RETURN NULL;
	END IF;

	RAISE NOTICE 'log table = %', log_table;
	EXECUTE 'INSERT INTO ' || log_table  || ' SELECT ($1).*' USING audit_row;

	RETURN NULL;
END;
$body$
LANGUAGE plpgsql
SECURITY DEFINER
SET search_path = pg_catalog, public;


CREATE OR REPLACE FUNCTION audit.audit_table(
	source_table regclass, 
	target_table regclass, 
	audit_rows boolean, 
	audit_query_text boolean, 
	ignored_cols text[]
) RETURNS void AS $body$
DECLARE
  stm_targets text = 'INSERT OR UPDATE OR DELETE OR TRUNCATE';
  _q_txt text;
  _ignored_cols_snip text = '';
BEGIN
	EXECUTE 'DROP TRIGGER IF EXISTS audit_trigger_row ON ' || source_table;
	EXECUTE 'DROP TRIGGER IF EXISTS audit_trigger_stm ON ' || source_table;

	IF audit_rows THEN
	
		IF array_length(ignored_cols,1) > 0 THEN
			_ignored_cols_snip = _ignored_cols_snip || ', ' || quote_literal(ignored_cols);
		END IF;
		
		_q_txt = 'CREATE TRIGGER audit_trigger_row AFTER INSERT OR UPDATE OR DELETE ON ' || source_table || 
				 ' FOR EACH ROW EXECUTE PROCEDURE audit.if_modified_func(' 
					 || quote_literal(source_table) 
					 || ',' || quote_literal(target_table) 
					 || ',' || quote_literal(audit_query_text) 
					 || _ignored_cols_snip 
				   || ');';

		RAISE NOTICE '%',_q_txt;
		EXECUTE _q_txt;
		stm_targets = 'TRUNCATE';
	ELSE
	END IF;

	_q_txt = 'CREATE TRIGGER audit_trigger_stm AFTER ' || stm_targets || ' ON ' ||
			 source_table ||
			 ' FOR EACH STATEMENT EXECUTE PROCEDURE audit.if_modified_func('
				 || quote_literal(source_table) 
				 || ',' || quote_literal(target_table) 
				 || ',' || audit_query_text
			   || ');';
	RAISE NOTICE '%',_q_txt;
	EXECUTE _q_txt;

END;
$body$
language 'plpgsql';


CREATE OR REPLACE FUNCTION audit.unaudit(target_table regclass) RETURNS void AS $body$
DECLARE
BEGIN
	EXECUTE 'DROP TRIGGER IF EXISTS audit_trigger_row ON ' || target_table;
	EXECUTE 'DROP TRIGGER IF EXISTS audit_trigger_stm ON ' || target_table;
END;
$body$
language 'plpgsql';


-- And provide a convenience call wrapper for the simplest case
-- of row-level logging with no excluded cols and query logging enabled.
-- 
-- ex: select audit.audit_table('snis.rs_sit_cat', 'audit.log_rs');
--
CREATE OR REPLACE FUNCTION audit.audit_table(source_table regclass, target_table regclass) RETURNS void AS $$
  SELECT audit.audit_table($1, $2, BOOLEAN 't', BOOLEAN 't', ARRAY[]::text[]);
$$ LANGUAGE 'sql';

DROP VIEW IF EXISTS audit.vw_log;
CREATE VIEW audit.vw_log AS 
  SELECT 'audit.log' AS log_table, * FROM audit.log
  UNION ALL
  SELECT 'audit.drenagem' AS log_table, * FROM audit.drenagem
  UNION ALL
  SELECT 'audit._public' AS log_table, * FROM audit._public
  UNION ALL
  SELECT 'audit.acesso' AS log_table, * FROM audit.acesso
  UNION ALL
  SELECT 'audit.dicionario' AS log_table, * FROM audit.dicionario
;
-----------------------------------------------------------------------------------------------------------------
-- select * from audit.vw_log

--select row_data->'nom_login' as nom_login, row_data->'cod_psv' as cod_psv, * from audit.vw_log order by event_id desc;
--update psv_ger set ge098 = '' where cod_psv = '16003000' and ano_ref = '2014'

select audit.unaudit('drenagem.rlc_outras_entidades'::regclass);
select audit.unaudit('drenagem.tab_avaliacoes_reacoes'::regclass);
select audit.unaudit('drenagem.tab_campo_multi_opcoes'::regclass);
select audit.unaudit('drenagem.tab_campos_auxiliares'::regclass);
select audit.unaudit('drenagem.tab_cobrancas'::regclass);
select audit.unaudit('drenagem.tab_corpos_receptores'::regclass);
select audit.unaudit('drenagem.tab_financeiros'::regclass);
select audit.unaudit('drenagem.tab_gerais'::regclass);
select audit.unaudit('drenagem.tab_gerais_bacias_hidrograficas'::regclass);
select audit.unaudit('drenagem.tab_gerais_rios'::regclass);
select audit.unaudit('drenagem.tab_infraestruturas'::regclass);
select audit.unaudit('drenagem.tab_infraestruturas_bacias'::regclass);
select audit.unaudit('drenagem.tab_infraestruturas_parques'::regclass);
select audit.unaudit('drenagem.tab_municipios_atendidos'::regclass);
select audit.unaudit('drenagem.tab_operacionais'::regclass);
select audit.unaudit('drenagem.tab_operacionais_intervencoes'::regclass);
select audit.unaudit('drenagem.tab_parametros'::regclass);
select audit.unaudit('drenagem.tab_parques_lineares'::regclass);
select audit.unaudit('drenagem.tab_participacoes'::regclass);
select audit.unaudit('drenagem.tab_retencao_vazoes'::regclass);
select audit.unaudit('drenagem.tab_riscos'::regclass);

-- DICIONARIO --
select audit.unaudit('dicionario.rlc_avisos_erros_glossarios'::regclass);
select audit.unaudit('dicionario.tab_avisos_erros'::regclass);
select audit.unaudit('dicionario.tab_bloco_info'::regclass);
select audit.unaudit('dicionario.tab_form'::regclass);
select audit.unaudit('dicionario.tab_glossarios'::regclass);

-- PUBLIC --
select audit.unaudit('public.rlc_modulos_prestadores'::regclass);
select audit.unaudit('public.tab_atributos'::regclass);
select audit.unaudit('public.tab_atributos_valores'::regclass);
select audit.unaudit('public.tab_contatos'::regclass);
select audit.unaudit('public.tab_estados'::regclass);
select audit.unaudit('public.tab_faixas_populacionais'::regclass);
select audit.unaudit('public.tab_mala_direta'::regclass);
select audit.unaudit('public.tab_mala_direta_log'::regclass);
select audit.unaudit('public.tab_mesorregioes'::regclass);
select audit.unaudit('public.tab_microrregioes'::regclass);
select audit.unaudit('public.tab_modelo_docs'::regclass);
select audit.unaudit('public.tab_municipios'::regclass);
select audit.unaudit('public.tab_municipios_atendidos'::regclass);
select audit.unaudit('public.tab_municipios_desastres'::regclass);
select audit.unaudit('public.tab_municipios_populacoes'::regclass);
select audit.unaudit('public.tab_parametros'::regclass);
select audit.unaudit('public.tab_prestadores'::regclass);
select audit.unaudit('public.tab_regioes_geograficas'::regclass);
select audit.unaudit('public.tab_regioes_metropolitanas'::regclass);

-- ACESSO --
select audit.unaudit('acesso.rlc_menus_perfis'::regclass);
select audit.unaudit('acesso.rlc_perfis_funcionalidades_acoes'::regclass);
select audit.unaudit('acesso.rlc_usuarios_perfis'::regclass);
select audit.unaudit('acesso.tab_acoes'::regclass);
select audit.unaudit('acesso.tab_funcionalidades'::regclass);
select audit.unaudit('acesso.tab_menus'::regclass);
select audit.unaudit('acesso.tab_modulos'::regclass);
select audit.unaudit('acesso.tab_perfis'::regclass);
select audit.unaudit('acesso.tab_restricoes_usuarios'::regclass);
select audit.unaudit('acesso.tab_usuarios'::regclass);
select audit.unaudit('acesso.tab_usuarios_opcoes'::regclass);









-- DRENAGEM --
select audit.audit_table('drenagem.rlc_outras_entidades', 'audit.drenagem');
select audit.audit_table('drenagem.tab_avaliacoes_reacoes', 'audit.drenagem');
select audit.audit_table('drenagem.tab_campo_multi_opcoes', 'audit.drenagem');
select audit.audit_table('drenagem.tab_campos_auxiliares', 'audit.drenagem');
select audit.audit_table('drenagem.tab_cobrancas', 'audit.drenagem');
select audit.audit_table('drenagem.tab_corpos_receptores', 'audit.drenagem');
select audit.audit_table('drenagem.tab_financeiros', 'audit.drenagem');
select audit.audit_table('drenagem.tab_gerais', 'audit.drenagem');
select audit.audit_table('drenagem.tab_gerais_bacias_hidrograficas', 'audit.drenagem');
select audit.audit_table('drenagem.tab_gerais_rios', 'audit.drenagem');
select audit.audit_table('drenagem.tab_infraestruturas', 'audit.drenagem');
select audit.audit_table('drenagem.tab_infraestruturas_bacias', 'audit.drenagem');
select audit.audit_table('drenagem.tab_infraestruturas_parques', 'audit.drenagem');
select audit.audit_table('drenagem.tab_municipios_atendidos', 'audit.drenagem');
select audit.audit_table('drenagem.tab_operacionais', 'audit.drenagem');
select audit.audit_table('drenagem.tab_operacionais_intervencoes', 'audit.drenagem');
select audit.audit_table('drenagem.tab_parametros', 'audit.drenagem');
select audit.audit_table('drenagem.tab_parques_lineares', 'audit.drenagem');
select audit.audit_table('drenagem.tab_participacoes', 'audit.drenagem');
select audit.audit_table('drenagem.tab_retencao_vazoes', 'audit.drenagem');
select audit.audit_table('drenagem.tab_riscos', 'audit.drenagem');

-- DICIONARIO --
select audit.audit_table('dicionario.rlc_avisos_erros_glossarios', 'audit.dicionario');
select audit.audit_table('dicionario.tab_avisos_erros', 'audit.dicionario');
select audit.audit_table('dicionario.tab_bloco_info', 'audit.dicionario');
select audit.audit_table('dicionario.tab_form', 'audit.dicionario');
select audit.audit_table('dicionario.tab_glossarios', 'audit.dicionario');


-- PUBLIC --
select audit.audit_table('public.rlc_modulos_prestadores', 'audit._public');
select audit.audit_table('public.tab_atributos', 'audit._public');
select audit.audit_table('public.tab_atributos_valores', 'audit._public');
select audit.audit_table('public.tab_contatos', 'audit._public');
select audit.audit_table('public.tab_estados', 'audit._public');
select audit.audit_table('public.tab_faixas_populacionais', 'audit._public');
select audit.audit_table('public.tab_mala_direta', 'audit._public');
select audit.audit_table('public.tab_mala_direta_log', 'audit._public');
select audit.audit_table('public.tab_mesorregioes', 'audit._public');
select audit.audit_table('public.tab_microrregioes', 'audit._public');
select audit.audit_table('public.tab_modelo_docs', 'audit._public');
select audit.audit_table('public.tab_municipios', 'audit._public');
select audit.audit_table('public.tab_municipios_atendidos', 'audit._public');
select audit.audit_table('public.tab_municipios_desastres', 'audit._public');
select audit.audit_table('public.tab_municipios_populacoes', 'audit._public');
select audit.audit_table('public.tab_parametros', 'audit._public');
select audit.audit_table('public.tab_prestadores', 'audit._public');
select audit.audit_table('public.tab_regioes_geograficas', 'audit._public');
select audit.audit_table('public.tab_regioes_metropolitanas', 'audit._public');

-- ACESSO --
select audit.audit_table('acesso.rlc_menus_perfis', 'audit.acesso');
select audit.audit_table('acesso.rlc_perfis_funcionalidades_acoes', 'audit.acesso');
select audit.audit_table('acesso.rlc_usuarios_perfis', 'audit.acesso');
select audit.audit_table('acesso.tab_acoes', 'audit.acesso');
select audit.audit_table('acesso.tab_funcionalidades', 'audit.acesso');
select audit.audit_table('acesso.tab_menus', 'audit.acesso');
select audit.audit_table('acesso.tab_modulos', 'audit.acesso');
select audit.audit_table('acesso.tab_perfis', 'audit.acesso');
select audit.audit_table('acesso.tab_restricoes_usuarios', 'audit.acesso');
select audit.audit_table('acesso.tab_usuarios', 'audit.acesso');
select audit.audit_table('acesso.tab_usuarios_opcoes', 'audit.acesso');








select * from audit.vw_log;




