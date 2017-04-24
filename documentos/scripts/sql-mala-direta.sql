
SET search_path = public;

DROP TABLE IF EXISTS tab_mala_direta CASCADE;
CREATE TABLE tab_mala_direta (
  cod_mala_direta serial NOT NULL,
  servico_fk int not null,
  status_fk smallint not null, -- AV -> status-mala-direta
  remetente_fk smallint not null, -- AV -> remetentes-mala-direta
  
  txt_titulo_mala character varying(100) NOT NULL,
  txt_titulo_email character varying(100) NOT NULL,
  txt_conteudo_email text not null,
  txt_contatos_json text not null default '[]',
  txt_anexos_json text,
  txt_oficio text,

  txt_login_inclusao character varying(150), -- Usuário da Inclusão
  dte_inclusao timestamp without time zone DEFAULT now(), -- Data da Inclusão
  dte_alteracao timestamp without time zone, -- Data da Alteração
  dte_exclusao timestamp without time zone, -- Data da Exclusão
  
  PRIMARY KEY (cod_mala_direta),
  
  FOREIGN KEY (status_fk)
    REFERENCES public.tab_atributos_valores (cod_atributos_valores)
    ON UPDATE RESTRICT ON DELETE RESTRICT,

  FOREIGN KEY (servico_fk)
    REFERENCES public.tab_atributos_valores (cod_atributos_valores)
    ON DELETE RESTRICT ON UPDATE RESTRICT,

  FOREIGN KEY (remetente_fk)
    REFERENCES public.tab_atributos_valores (cod_atributos_valores)
    ON DELETE RESTRICT ON UPDATE RESTRICT
);

DROP TABLE IF EXISTS tab_mala_direta_log;
CREATE TABLE tab_mala_direta_log (
  cod_mala_direta_log serial NOT NULL,\
  mala_direta_fk int NOT NULL,
  prestador_fk int not null,
  modulo_fk int not null,
  status_fk smallint not null, -- AV -> status-registro-envio-mala-direta
  tipo_contato_fk smallint NOT NULL, -- AV -> tipos-contato-prestador-servicos

  txt_nome varchar (100),
  txt_cargo varchar (100),
  txt_email varchar (150),
  num_ano_ref smallint NOT NULL,
  txt_msg_erro varchar,
  bln_oficio_gerado boolean not null default false,

  txt_login_inclusao character varying(150), -- Usuário da Inclusão
  dte_inclusao timestamp without time zone DEFAULT now(), -- Data da Inclusão
  dte_alteracao timestamp without time zone, -- Data da Alteração
  dte_exclusao timestamp without time zone, -- Data da Exclusão

  PRIMARY KEY (cod_mala_direta_log),

  FOREIGN KEY (mala_direta_fk)
    REFERENCES tab_mala_direta (cod_mala_direta)
    ON DELETE RESTRICT ON UPDATE RESTRICT,

  FOREIGN KEY (prestador_fk)
    REFERENCES public.tab_prestadores (cod_prestador)
    ON DELETE RESTRICT ON UPDATE RESTRICT,

  FOREIGN KEY (modulo_fk)
    REFERENCES acesso.tab_modulos (cod_modulo)
    ON DELETE RESTRICT ON UPDATE RESTRICT,

  FOREIGN KEY (status_fk)
    REFERENCES public.tab_atributos_valores (cod_atributos_valores)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
    
  FOREIGN KEY (tipo_contato_fk)
    REFERENCES public.tab_atributos_valores (cod_atributos_valores)
    ON UPDATE RESTRICT ON DELETE RESTRICT
);
/*
insert into tab_mala_direta (
  servico_fk,
  status_fk,
  remetente_fk,
  
  txt_titulo_mala,
  txt_titulo_email,
  txt_conteudo_email
) values (
  14,
  161,
  167,
  'Inscrição no curso EaD Série Histórica',
  'Inscrição no curso EaD Série Histórica',
  '<p>this is madness</p>'
);


insert into tab_mala_direta_log (
  mala_direta_fk,
  prestador_fk,
  modulo_fk,
  tipo_contato_fk,
  
  txt_nome,
  txt_cargo,
  txt_email,
  num_ano_ref,
  status_fk
)
select   7 as mala_direta_fk
	,cod_prestador as prestador_fk
	,2 as modulo_fk
	,173 as tipo_contato_fk
	,txt_mandatario_nome as txt_nome
	,txt_mandatario_cargo as txt_cargo
-- 	,txt_mandatario_email as txt_email
-- 	,'anderson.priedols@cidades.gov.br' as txt_email
	,'ameggiolaro@gmail.com' as txt_email
	,2014 as num_ano_ref
	,169 as status_fk
from tab_prestadores 
order by random()
limit 3
 */
-- select * from tab_mala_direta_log where mala_direta_fk = 2
-- update tab_mala_direta_log set txt_email = 'ameggiolaro@gmail.com'
-- update tab_mala_direta set status_fk = 161
-- delete from tab_mala_direta_log

--select * from tab_mala_direta
--update tab_mala_direta set servico_fk = 13 where cod_mala_direta = 2
--update tab_mala_direta_log set bln_oficio_gerado = false
--

--alter table tab_mala_direta_log add column bln_oficio_gerado boolean not null default false;

select * from public.vis_prestadores_contatos