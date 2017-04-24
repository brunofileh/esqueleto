CREATE TABLE public.tab_regioes_geograficas (
                cod_regiao_geografica BIGINT NOT NULL,
                sgl_regiao_geografica VARCHAR NOT NULL,
                txt_nome VARCHAR NOT NULL,
                CONSTRAINT pk_tab_regioes_geograficas PRIMARY KEY (cod_regiao_geografica)
);
COMMENT ON TABLE public.tab_regioes_geograficas IS 'Regioes geograficas';
COMMENT ON COLUMN public.tab_regioes_geograficas.cod_regiao_geografica IS 'Código da Região Geográfica';
COMMENT ON COLUMN public.tab_regioes_geograficas.sgl_regiao_geografica IS 'Sigla';
COMMENT ON COLUMN public.tab_regioes_geograficas.txt_nome IS 'Nome da Região Geográfica';

CREATE INDEX idx_reg_geo_cod_reg_geo
 ON public.tab_regioes_geograficas USING BTREE
 ( cod_regiao_geografica );

CREATE INDEX idx_reg_geo_sgl_reg_geo
 ON public.tab_regioes_geograficas USING BTREE
 ( sgl_regiao_geografica );

CREATE SEQUENCE public.tab_regioes_metropolitanas_cod_regiao_metropolitana_seq;

CREATE TABLE public.tab_regioes_metropolitanas (
                cod_regiao_metropolitana INTEGER NOT NULL DEFAULT nextval('public.tab_regioes_metropolitanas_cod_regiao_metropolitana_seq'),
                txt_nome VARCHAR(2000) NOT NULL,
                CONSTRAINT pk_regiao_metropolitana PRIMARY KEY (cod_regiao_metropolitana)
);
COMMENT ON COLUMN public.tab_regioes_metropolitanas.cod_regiao_metropolitana IS 'Código da região metropolitana';
COMMENT ON COLUMN public.tab_regioes_metropolitanas.txt_nome IS 'Nome da Região Metropolitana';

ALTER SEQUENCE public.tab_regioes_metropolitanas_cod_regiao_metropolitana_seq OWNED BY public.tab_regioes_metropolitanas.cod_regiao_metropolitana;

CREATE UNIQUE INDEX idx_composta_regiao_metropolitana
 ON public.tab_regioes_metropolitanas USING BTREE
 ( txt_nome );

CREATE INDEX idx_regiao_metropolitana
 ON public.tab_regioes_metropolitanas USING BTREE
 ( cod_regiao_metropolitana );

CREATE TABLE public.tab_estados (
                sgl_estado CHAR(2) NOT NULL,
                cod_estado CHAR(2) NOT NULL,
                txt_nome VARCHAR(20) NOT NULL,
                cod_cpt_est CHAR(6) NOT NULL,
                qtd_mun_est INTEGER,
                vlr_taxa_hab_dom NUMERIC(18,2),
                cod_regiao_geografica BIGINT,
                CONSTRAINT pk_tab_estados PRIMARY KEY (sgl_estado)
);
COMMENT ON TABLE public.tab_estados IS 'estado';
COMMENT ON COLUMN public.tab_estados.sgl_estado IS 'SIgla do Estado';
COMMENT ON COLUMN public.tab_estados.cod_estado IS 'COD_EST';
COMMENT ON COLUMN public.tab_estados.txt_nome IS 'Nome do estado';
COMMENT ON COLUMN public.tab_estados.cod_cpt_est IS 'COD_CPT_EST';
COMMENT ON COLUMN public.tab_estados.qtd_mun_est IS 'QTD_MUN_EST';
COMMENT ON COLUMN public.tab_estados.vlr_taxa_hab_dom IS 'TX_HAB_DOM';
COMMENT ON COLUMN public.tab_estados.cod_regiao_geografica IS 'Código da Região Geográfica';

CREATE INDEX idx_nom_est
 ON public.tab_estados USING BTREE
 ( txt_nome );

CREATE TABLE public.tab_mesorregioes (
                cod_mesorregiao INTEGER NOT NULL,
                cod_estado_fk INTEGER NOT NULL,
                txt_nome VARCHAR(255) NOT NULL,
                CONSTRAINT pk_mesorregioes PRIMARY KEY (cod_mesorregiao)
);
COMMENT ON TABLE public.tab_mesorregioes IS 'mesorregioes';
COMMENT ON COLUMN public.tab_mesorregioes.cod_mesorregiao IS 'Código Mesoregião';
COMMENT ON COLUMN public.tab_mesorregioes.cod_estado_fk IS 'Código do Estado';
COMMENT ON COLUMN public.tab_mesorregioes.txt_nome IS 'Nome';

CREATE TABLE public.tab_microrregioes (
                cod_microrregiao INTEGER NOT NULL,
                fk_mesorregiao INTEGER NOT NULL,
                txt_nome VARCHAR(255) NOT NULL,
                CONSTRAINT tab_pk_microrregioes PRIMARY KEY (cod_microrregiao)
);
COMMENT ON TABLE public.tab_microrregioes IS 'microrregioes';
COMMENT ON COLUMN public.tab_microrregioes.cod_microrregiao IS 'Código Microregiao';
COMMENT ON COLUMN public.tab_microrregioes.fk_mesorregiao IS 'fk_mesorregiao';
COMMENT ON COLUMN public.tab_microrregioes.txt_nome IS 'Nome';

CREATE TABLE public.tab_municipios (
                cod_municipio CHAR(6) NOT NULL,
                txt_nome VARCHAR(50),
                sgl_estado_fk CHAR(2),
                bln_indicador_capital BOOLEAN DEFAULT false,
                cod_microrregiao_fk INTEGER,
                cod_ibge INTEGER,
                cod_regiao_metropolitana_fk INTEGER,
                CONSTRAINT pk_municipios PRIMARY KEY (cod_municipio)
);
COMMENT ON TABLE public.tab_municipios IS 'municipios';
COMMENT ON COLUMN public.tab_municipios.cod_municipio IS 'Código do munípio';
COMMENT ON COLUMN public.tab_municipios.txt_nome IS 'Nome do município';
COMMENT ON COLUMN public.tab_municipios.sgl_estado_fk IS 'Sigla município';
COMMENT ON COLUMN public.tab_municipios.bln_indicador_capital IS 'Se capital';
COMMENT ON COLUMN public.tab_municipios.cod_microrregiao_fk IS 'Microregião';
COMMENT ON COLUMN public.tab_municipios.cod_ibge IS 'Código do IBGE';
COMMENT ON COLUMN public.tab_municipios.cod_regiao_metropolitana_fk IS 'Região Metropolitana';

CREATE INDEX idx_municipio_cod_mun
 ON public.tab_municipios USING BTREE
 ( cod_municipio );

CREATE INDEX idx_municipio_sgl_est
 ON public.tab_municipios USING BTREE
 ( sgl_estado_fk );

CREATE INDEX idx_nom_mun
 ON public.tab_municipios USING BTREE
 ( txt_nome );

CREATE SEQUENCE public.tab_prestadores_cod_prestador_seq;

CREATE TABLE public.tab_prestadores (
                cod_prestador BIGINT NOT NULL DEFAULT nextval('public.tab_prestadores_cod_prestador_seq'),
                txt_ano_cadastro CHAR(4),
                txt_nome VARCHAR(100),
                txt_sigla VARCHAR(20),
                txt_endereco VARCHAR(100),
                txt_bairro VARCHAR(50),
                txt_complemento VARCHAR(50),
                num_numero VARCHAR(8),
                num_cep VARCHAR(10),
                txt_site VARCHAR(70),
                num_inscricao_federal VARCHAR(20),
                num_inscricao_estadual VARCHAR(20),
                num_inscricao_municipal VARCHAR(20),
                txt_observacoes VARCHAR(2000),
                dte_exclusao TIMESTAMP,
                txt_motivo_exclusao VARCHAR(255),
                txt_login_inclusao VARCHAR(150),
                txt_ultimo_ano_coleta INTEGER,
                cod_municipio_fk CHAR(6) NOT NULL,
                CONSTRAINT pk_tab_prestadores PRIMARY KEY (cod_prestador)
);
COMMENT ON TABLE public.tab_prestadores IS 'Tabela de prestadores';
COMMENT ON COLUMN public.tab_prestadores.cod_prestador IS 'Código do prestador';
COMMENT ON COLUMN public.tab_prestadores.txt_ano_cadastro IS 'Ano de cadastro';
COMMENT ON COLUMN public.tab_prestadores.txt_nome IS 'Nome';
COMMENT ON COLUMN public.tab_prestadores.txt_sigla IS 'Sigla';
COMMENT ON COLUMN public.tab_prestadores.txt_endereco IS 'Endereco';
COMMENT ON COLUMN public.tab_prestadores.txt_bairro IS 'Bairro';
COMMENT ON COLUMN public.tab_prestadores.txt_complemento IS 'Complemento';
COMMENT ON COLUMN public.tab_prestadores.num_numero IS 'Número';
COMMENT ON COLUMN public.tab_prestadores.num_cep IS 'CEP';
COMMENT ON COLUMN public.tab_prestadores.txt_site IS 'Site';
COMMENT ON COLUMN public.tab_prestadores.num_inscricao_federal IS 'Inscricao Federal';
COMMENT ON COLUMN public.tab_prestadores.num_inscricao_estadual IS 'Inscricao Estadual';
COMMENT ON COLUMN public.tab_prestadores.num_inscricao_municipal IS 'Inscricao municipal';
COMMENT ON COLUMN public.tab_prestadores.txt_observacoes IS 'Observações';
COMMENT ON COLUMN public.tab_prestadores.dte_exclusao IS 'Data da Exclusão';
COMMENT ON COLUMN public.tab_prestadores.txt_motivo_exclusao IS 'Motivo da Exclusão';
COMMENT ON COLUMN public.tab_prestadores.txt_login_inclusao IS 'Usuário da Inclusão';
COMMENT ON COLUMN public.tab_prestadores.txt_ultimo_ano_coleta IS 'Último ano em que o prestador foi convidado para coleta de dados antes de ser excluído.';
COMMENT ON COLUMN public.tab_prestadores.cod_municipio_fk IS 'Código do município';

ALTER SEQUENCE public.tab_prestadores_cod_prestador_seq OWNED BY public.tab_prestadores.cod_prestador;

CREATE INDEX idx_prestador_cod_psv
 ON public.tab_prestadores USING BTREE
 ( cod_prestador );

CREATE SEQUENCE public.rlc_modulos_prestadores_cod_modulo_prestador_seq;

CREATE TABLE public.rlc_modulos_prestadores (
                cod_modulo_prestador BIGINT NOT NULL DEFAULT nextval('public.rlc_modulos_prestadores_cod_modulo_prestador_seq'),
                cod_prestador_fk BIGINT NOT NULL,
                cod_modulo_fk BIGINT NOT NULL,
                CONSTRAINT rlc_modulos_prestadores_pk PRIMARY KEY (cod_modulo_prestador)
);
COMMENT ON TABLE public.rlc_modulos_prestadores IS 'relacionamento de muito p muitos entre modulos e prestadores';
COMMENT ON COLUMN public.rlc_modulos_prestadores.cod_modulo_prestador IS 'Código do Modulo Prestador';
COMMENT ON COLUMN public.rlc_modulos_prestadores.cod_prestador_fk IS 'Codigo do prestador';

ALTER SEQUENCE public.rlc_modulos_prestadores_cod_modulo_prestador_seq OWNED BY public.rlc_modulos_prestadores.cod_modulo_prestador;

CREATE SEQUENCE public.tab_participacoes_cod_participacao_seq;

CREATE TABLE public.tab_participacoes (
                cod_participacao BIGINT NOT NULL DEFAULT nextval('public.tab_participacoes_cod_participacao_seq'),
                cod_modulo_prestador_fk BIGINT NOT NULL,
                ano_ref CHAR(4) NOT NULL,
                cod_abrangencia_fk CHAR(1) NOT NULL,
                cod_natatureza_fk CHAR(1) NOT NULL,
                bln_convidade CHAR(1) NOT NULL,
                bln_publicado CHAR(1) NOT NULL,
                cod_situacao_preenchimento_fk BIGINT NOT NULL,
                bln_pesquisa CHAR(1),
                txt_login_inclusao VARCHAR(150),
                dte_inclusao TIMESTAMP,
                dte_alteracao TIMESTAMP,
                bln_nao_resp_ano_ant CHAR(1) DEFAULT '1'::bpchar NOT NULL,
                txt_observacao VARCHAR(2000),
                CONSTRAINT pk_tab_participacoes PRIMARY KEY (cod_participacao)
);
COMMENT ON TABLE public.tab_participacoes IS 'tab_participacoes';
COMMENT ON COLUMN public.tab_participacoes.cod_participacao IS 'Código da participação';
COMMENT ON COLUMN public.tab_participacoes.cod_modulo_prestador_fk IS 'Código do Modulo Prestador';
COMMENT ON COLUMN public.tab_participacoes.ano_ref IS 'Ano de Referência';
COMMENT ON COLUMN public.tab_participacoes.cod_abrangencia_fk IS 'Código da Abrangência';
COMMENT ON COLUMN public.tab_participacoes.cod_natatureza_fk IS 'Código da Natureza';
COMMENT ON COLUMN public.tab_participacoes.bln_convidade IS 'Determina se o prestador foi convidado a fornecer as informacoes no ano de referencia';
COMMENT ON COLUMN public.tab_participacoes.bln_publicado IS 'Determina se o prestador tera seus dados publicados Se respondeu a coleta no ano de referencia';
COMMENT ON COLUMN public.tab_participacoes.cod_situacao_preenchimento_fk IS 'Determina a situacao do preenchimento da coleta de dados
0  Nao iniciado
1  Preenchimento sendo realizado pelo prestador
2  Finalizado por arquivo resposta
3  Preenchimento finalizado pelo prestador
4  Preenchimento em analise pelo consultor
5  Preenchimento analizado pelo consultor e finalizado';
COMMENT ON COLUMN public.tab_participacoes.bln_pesquisa IS 'pesquisa
1  Atende Esgoto Existe coleta pblica de esgoto
2  No possui coleta  e esgoto pblico
Quando cod_serv  3 e pesquisa  2 deve ser nulo';
COMMENT ON COLUMN public.tab_participacoes.txt_login_inclusao IS 'Usuário da Inclusão';
COMMENT ON COLUMN public.tab_participacoes.dte_inclusao IS 'Data da Inclusão';
COMMENT ON COLUMN public.tab_participacoes.dte_alteracao IS 'Data da Alteração';
COMMENT ON COLUMN public.tab_participacoes.bln_nao_resp_ano_ant IS 'Se respondeu a pesquisa ano anterior';
COMMENT ON COLUMN public.tab_participacoes.txt_observacao IS 'Observação';

ALTER SEQUENCE public.tab_participacoes_cod_participacao_seq OWNED BY public.tab_participacoes.cod_participacao;

CREATE INDEX idx_pesquisa
 ON public.tab_participacoes USING BTREE
 ( bln_pesquisa );

CREATE INDEX idx_psv_part_ano_ref
 ON public.tab_participacoes USING BTREE
 ( ano_ref );

CREATE INDEX idx_psv_part_cod_abr
 ON public.tab_participacoes USING BTREE
 ( cod_abrangencia_fk );

CREATE INDEX idx_psv_part_cod_nat
 ON public.tab_participacoes USING BTREE
 ( cod_natatureza_fk );

CREATE INDEX idx_psv_pub
 ON public.tab_participacoes USING BTREE
 ( bln_publicado );

CREATE INDEX idx_sit_preenchimento
 ON public.tab_participacoes USING BTREE
 ( cod_situacao_preenchimento_fk );

CREATE SEQUENCE public.tab_municipios_atendidos_cod_mum_atdm_seq;

CREATE TABLE public.tab_municipios_atendidos (
                cod_municipio_atendido BIGINT NOT NULL DEFAULT nextval('public.tab_municipios_atendidos_cod_mum_atdm_seq'),
                cod_participacao BIGINT NOT NULL,
                ge019 CHAR(1),
                ge020 CHAR(1),
                txt_tipo_dados VARCHAR NOT NULL,
                cod_municipio_fk CHAR(6) NOT NULL,
                txt_login_inclusao VARCHAR(150),
                dte_inclusao TIMESTAMP,
                dte_alteracao TIMESTAMP,
                CONSTRAINT pk_tab_municipios_atendidos PRIMARY KEY (cod_municipio_atendido)
);
COMMENT ON TABLE public.tab_municipios_atendidos IS 'Município atendido pelo prestador';
COMMENT ON COLUMN public.tab_municipios_atendidos.cod_municipio_atendido IS 'Código do Município Atendido';
COMMENT ON COLUMN public.tab_municipios_atendidos.cod_participacao IS 'Código da participação';
COMMENT ON COLUMN public.tab_municipios_atendidos.ge019 IS 'GE019';
COMMENT ON COLUMN public.tab_municipios_atendidos.ge020 IS 'GE020';
COMMENT ON COLUMN public.tab_municipios_atendidos.txt_tipo_dados IS 'Tipo dados - agregados ou desagregados';
COMMENT ON COLUMN public.tab_municipios_atendidos.cod_municipio_fk IS 'Código do munípio';
COMMENT ON COLUMN public.tab_municipios_atendidos.txt_login_inclusao IS 'Usuário da Inclusão';
COMMENT ON COLUMN public.tab_municipios_atendidos.dte_inclusao IS 'Data da Inclusão';
COMMENT ON COLUMN public.tab_municipios_atendidos.dte_alteracao IS 'Data da Alteração';

ALTER SEQUENCE public.tab_municipios_atendidos_cod_mum_atdm_seq OWNED BY public.tab_municipios_atendidos.cod_municipio_atendido;

ALTER TABLE public.tab_estados ADD CONSTRAINT tab_regioes_geograficas_tab_estados_fk
FOREIGN KEY (cod_regiao_geografica)
REFERENCES public.tab_regioes_geograficas (cod_regiao_geografica)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.tab_municipios ADD CONSTRAINT fk_reg_met
FOREIGN KEY (cod_regiao_metropolitana_fk)
REFERENCES public.tab_regioes_metropolitanas (cod_regiao_metropolitana)
ON DELETE RESTRICT
ON UPDATE RESTRICT
NOT DEFERRABLE;

ALTER TABLE public.tab_municipios ADD CONSTRAINT fk_muni_esta
FOREIGN KEY (sgl_estado_fk)
REFERENCES public.tab_estados (sgl_estado)
ON DELETE NO ACTION
ON UPDATE CASCADE
NOT DEFERRABLE;

ALTER TABLE public.tab_microrregioes ADD CONSTRAINT fk_microrre_rf_meso_m_mesorreg
FOREIGN KEY (fk_mesorregiao)
REFERENCES public.tab_mesorregioes (cod_mesorregiao)
ON DELETE RESTRICT
ON UPDATE RESTRICT
NOT DEFERRABLE;

ALTER TABLE public.tab_municipios ADD CONSTRAINT fk_municipio_microrre
FOREIGN KEY (cod_microrregiao_fk)
REFERENCES public.tab_microrregioes (cod_microrregiao)
ON DELETE RESTRICT
ON UPDATE RESTRICT
NOT DEFERRABLE;

ALTER TABLE public.tab_prestadores ADD CONSTRAINT tab_municipios_tab_prestadores_fk
FOREIGN KEY (cod_municipio_fk)
REFERENCES public.tab_municipios (cod_municipio)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.tab_municipios_atendidos ADD CONSTRAINT tab_municipios_tab_mun_atdm_fk
FOREIGN KEY (cod_municipio_fk)
REFERENCES public.tab_municipios (cod_municipio)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.rlc_modulos_prestadores ADD CONSTRAINT tab_prestadores_rlc_modulos_prestadores_fk
FOREIGN KEY (cod_prestador_fk)
REFERENCES public.tab_prestadores (cod_prestador)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.rlc_modulos_prestadores ADD CONSTRAINT tab_modulos_rlc_modulos_prestadores_fk
FOREIGN KEY (cod_modulo_fk)
REFERENCES acesso.tab_modulos (cod_modulo)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.tab_participacoes ADD CONSTRAINT rlc_modulos_prestadores_tab_participacoes_fk
FOREIGN KEY (cod_modulo_prestador_fk)
REFERENCES public.rlc_modulos_prestadores (cod_modulo_prestador)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.tab_municipios_atendidos ADD CONSTRAINT tab_participacoes_tab_mun_atdm_fk
FOREIGN KEY (cod_participacao)
REFERENCES public.tab_participacoes (cod_participacao)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;