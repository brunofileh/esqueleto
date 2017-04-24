
-------------------------------- SCHEMA PUBLIC --------------------------------

-- ## ATRIBUTO VALOR ## --
DROP TABLE IF EXISTS tab_atributos CASCADE;
CREATE TABLE public.tab_atributos (
  cod_atributos serial NOT NULL, -- id
  dsc_descricao character varying, -- descricao das opcoes
  sgl_chave character varying, -- chave para facilitar o acesso

  PRIMARY KEY (cod_atributos),
  UNIQUE (sgl_chave)
);
CREATE INDEX public_tab_atributos_cod_atributos_idx ON public.tab_atributos (cod_atributos);
CREATE INDEX public_tab_atributos_sgl_chave_idx ON public.tab_atributos (sgl_chave);

DROP TABLE IF EXISTS public.tab_atributos_valores CASCADE;
CREATE TABLE public.tab_atributos_valores (
  cod_atributos_valores serial NOT NULL, -- id
  fk_atributos_valores_atributos_id integer, -- atributo
  sgl_valor varchar, -- valor
  dsc_descricao character varying (200), -- descricao
  
  PRIMARY KEY (cod_atributos_valores),
  FOREIGN KEY (fk_atributos_valores_atributos_id)
    REFERENCES public.tab_atributos (cod_atributos)
    ON UPDATE RESTRICT ON DELETE RESTRICT
);
CREATE INDEX public_tab_atributos_valores_cod_atributos_valores_idx ON public.tab_atributos_valores (cod_atributos_valores);
CREATE INDEX public_tab_atributos_valores_fk_atributos_valores_atributos_id_idx ON public.tab_atributos_valores (fk_atributos_valores_atributos_id);

DROP MATERIALIZED VIEW IF EXISTS public.vis_atributos_valores;
CREATE MATERIALIZED VIEW public.vis_atributos_valores AS (
  SELECT a.cod_atributos, 
         v.cod_atributos_valores, 
         a.dsc_descricao as descr_atributo, 
         a.sgl_chave, 
         sgl_valor, 
         v.dsc_descricao
  FROM public.tab_atributos a
  JOIN public.tab_atributos_valores v ON (a.cod_atributos = v.fk_atributos_valores_atributos_id)
  ORDER BY a.cod_atributos, sgl_valor
);
CREATE INDEX public_vis_atributos_valores_cod_atributos_idx ON public.vis_atributos_valores (cod_atributos);
CREATE INDEX public_vis_atributos_valores_sgl_chave_idx ON public.vis_atributos_valores (sgl_chave);

-- ## FIM - ATRIBUTO VALOR ## --


-- ## CAMPOS DE MULTIPLAS OPCOES ## --

DROP TABLE IF EXISTS drenagem.tab_campo_multi_opcoes;
CREATE TABLE drenagem.tab_campo_multi_opcoes
(
  cod_campo_multi_opcoes serial NOT NULL,
  atributos_valores_fk smallint NOT NULL,
  participacao_fk int not null,
  cod_sequencia_tabela_pai smallint NOT NULL,
  dsc_tabela character varying(60),
  sgl_cod_info character varying(5),
  num_ano_ref smallint,

  dte_inclusao timestamp without time zone DEFAULT now(),
  dte_alteracao timestamp without time zone,
  dte_exclusao timestamp without time zone,

  PRIMARY KEY (cod_campo_multi_opcoes),
  FOREIGN KEY (participacao_fk)
      REFERENCES drenagem.tab_participacoes (cod_participacao)
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  FOREIGN KEY (atributos_valores_fk)
      REFERENCES public.tab_atributos_valores (cod_atributos_valores)
      ON UPDATE RESTRICT ON DELETE RESTRICT
);
CREATE INDEX drenagem_tab_campo_multi_opcoes_cod_campo_multi_opcoes_idx ON drenagem.tab_campo_multi_opcoes (cod_campo_multi_opcoes);
CREATE INDEX drenagem_tab_campo_multi_opcoes_fk_atributos_valores_idx ON drenagem.tab_campo_multi_opcoes (atributos_valores_fk);
CREATE INDEX drenagem_tab_campo_multi_opcoes_cod_sequencia_tabela_pai_idx ON drenagem.tab_campo_multi_opcoes (cod_sequencia_tabela_pai);
-- ## FIM - CAMPOS DE MULTIPLAS OPCOES ## --




-------------------------------- SCHEMA DICIONARIO --------------------------------
DROP SCHEMA IF EXISTS dicionario CASCADE;
CREATE SCHEMA dicionario AUTHORIZATION "grp.projeto_own";

-- ## FORMULARIOS ## --
DROP TABLE IF EXISTS dicionario.tab_form;
CREATE TABLE dicionario.tab_form (
  cod_form serial NOT NULL,
  cod_tipo_servico int NOT NULL,
  dsc_form varchar (120) NOT NULL,
  dsc_det_form text,

  PRIMARY KEY (cod_form)
);
CREATE INDEX dicionario_tab_form_cod_form_idx ON dicionario.tab_form (cod_form);
CREATE INDEX dicionario_tab_form_cod_tipo_servico_idx ON dicionario.tab_form (cod_tipo_servico);
-- ## FIM - FORMULARIOS ## --


-- ## BLOCOS DE INFORMACAO ## --
DROP TABLE IF EXISTS dicionario.tab_bloco_info;
CREATE TABLE dicionario.tab_bloco_info (
   serial NOT NULL,
  dsc_titulo_bloco varchar (80), -- título do bloco
  num_ordem_bloco smallint NOT NULL default 0, -- ordem do bloco na tela
  txt_info_bloco text, -- informação que será exibida para o usuário

  PRIMARY KEY (cod_bloco_info)
);
CREATE INDEX dicionario_tab_bloco_info_cod_bloco_info_idx ON dicionario.tab_bloco_info (cod_bloco_info);
-- ## FIM - BLOCOS DE INFORMACAO ## --

-- ## GLOSSARIO ## --
DROP TABLE IF EXISTS dicionario.tab_glossarios CASCADE;
CREATE TABLE dicionario.tab_glossarios (
  cod_glossario bigserial NOT NULL, -- id [1,2,3,..]
  fk_attr_nat_info int NOT NULL, -- natureza da informação
  fk_attr_fam_info int NOT NULL, -- família da informação
  fk_attr_abr_info int NOT NULL, -- abrangência da informação
  fk_attr_servico bigint NOT NULL, -- tipo de serviço
  fk_glossario_form int NOT NULL, -- formulário onde a informação será coletada
  fk_glossario_bloco_info int NOT NULL, -- bloco/contêiner onde a informação estará contida visualmente
  fk_tipo_origem int NOT NULL, -- Tipo de origem da informação
  fk_und_info int, -- unidade da informação [toneladas, percentual, habitantes, etc.]
  fk_tipo_apresentacao int,
  fk_opt_apresentacao int,
  fk_tabela_db int NOT NULL, -- tabela onde a informação será armazenada [schema.table]
  
  num_ano_ref smallint NOT NULL, -- ano de referência [2014]
  sgl_cod_info char (5) NOT NULL, -- cod_info [FN001]
  dsc_nom_info varchar (200) NOT NULL, -- nome da informação
  dsc_det_info text, -- detalhes da informação
  dsc_ref_info varchar (255), -- referência da informação [X25; X27]
  num_ordem smallint default 0, -- ordem em que a infornmação aparecerá no bloco
  bln_obrigatorio boolean DEFAULT false, -- indica se o preenchimento da informação é obrigatório
  num_ano_primeira_coleta smallint not null, -- ano da primeira coelta da informação

  num_casas_decimais smallint, -- número de cadas decimais no caso de campos numéricos
  num_vlr_min smallint,
  num_vlr_max smallint,
  bln_info_ativa boolean NOT NULL DEFAULT true, -- indica se a informação estará ativada na coleta e se aparecerá no glossário impresso
  txt_formula_ag text, -- formula dos indicadores AG
  txt_formula_es text, -- formula dos indicadores ES
  txt_formula_ae text, -- formula dos indicadores AE
  txt_formula_rs text, -- formula dos indicadores RS
  txt_formula_dn text, -- formula dos indicadores DN
  txt_formula_tot text, -- formmúla para o campo totalizador (fk_tipo_origem = T)
  txt_comentario text, -- comentário

  PRIMARY KEY (cod_glossario),
  UNIQUE (sgl_cod_info, num_ano_ref),
  
  FOREIGN KEY (fk_attr_nat_info)
    REFERENCES public.tab_atributos_valores (cod_atributos_valores)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  
  FOREIGN KEY (fk_attr_fam_info)
    REFERENCES public.tab_atributos_valores (cod_atributos_valores)
    ON DELETE RESTRICT ON UPDATE RESTRICT,

  FOREIGN KEY (fk_attr_abr_info)
    REFERENCES public.tab_atributos_valores (cod_atributos_valores)
    ON DELETE RESTRICT ON UPDATE RESTRICT,

  FOREIGN KEY (fk_attr_servico)
    REFERENCES acesso.tab_modulos (cod_modulo)
    ON DELETE RESTRICT ON UPDATE RESTRICT,

  FOREIGN KEY (fk_glossario_form)
    REFERENCES dicionario.tab_form (cod_form)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  
  FOREIGN KEY (fk_glossario_bloco_info)
    REFERENCES dicionario.tab_bloco_info (cod_bloco_info)
    ON DELETE RESTRICT ON UPDATE RESTRICT,

  FOREIGN KEY (fk_tipo_origem)
    REFERENCES public.tab_atributos_valores (cod_atributos_valores)
    ON DELETE RESTRICT ON UPDATE RESTRICT,

  FOREIGN KEY (fk_und_info)
    REFERENCES public.tab_atributos_valores (cod_atributos_valores)
    ON DELETE RESTRICT ON UPDATE RESTRICT,

  FOREIGN KEY (fk_tipo_apresentacao)
    REFERENCES public.tab_atributos_valores (cod_atributos_valores)
    ON DELETE RESTRICT ON UPDATE RESTRICT,

  FOREIGN KEY (fk_opt_apresentacao)
    REFERENCES public.tab_atributos (cod_atributos)
    ON DELETE RESTRICT ON UPDATE RESTRICT,

  FOREIGN KEY (fk_tabela_db)
    REFERENCES public.tab_atributos_valores (cod_atributos_valores)
    ON DELETE RESTRICT ON UPDATE RESTRICT
);
CREATE INDEX dicionario_tab_glossarios_cod_glossario_idx ON dicionario.tab_glossarios (cod_glossario);
CREATE INDEX dicionario_tab_glossarios_num_ano_ref_idx ON dicionario.tab_glossarios (num_ano_ref);
CREATE INDEX dicionario_tab_glossarios_num_ano_ref_sgl_cod_info_idx ON dicionario.tab_glossarios (num_ano_ref, sgl_cod_info);
CREATE INDEX dicionario_tab_glossarios_fks_idx 
  ON dicionario.tab_glossarios (fk_attr_nat_info, fk_attr_fam_info, fk_attr_abr_info, fk_attr_servico, fk_glossario_form, fk_glossario_bloco_info, fk_tipo_origem);


DROP MATERIALIZED VIEW IF EXISTS dicionario.vis_glossarios CASCADE;
CREATE MATERIALIZED VIEW dicionario.vis_glossarios AS
    SELECT
          -- dados básicos
           g.cod_glossario
          ,g.num_ano_ref
          ,g.sgl_cod_info
          ,g.dsc_nom_info
          ,g.dsc_det_info
          ,g.num_ano_primeira_coleta
          -- chaves estrangeiras
          ,g.fk_tipo_origem
          ,og.dsc_descricao AS dsc_tipo_origem
          ,og.sgl_valor AS sgl_tipo_origem
          ,g.fk_und_info
          ,ui.dsc_descricao AS dsc_und_info
          ,ui.sgl_valor AS sgl_und_info
          ,g.fk_attr_nat_info
          ,ni.dsc_descricao AS dsc_nat_info
          ,ni.sgl_valor AS sgl_nat_info
          ,g.fk_attr_fam_info
          ,fi.dsc_descricao AS dsc_fam_info
          ,fi.sgl_valor AS sgl_fam_info
          ,g.fk_attr_abr_info
          ,ai.dsc_descricao AS dsc_abr_info
          ,ai.sgl_valor AS sgl_abr_info
          ,g.fk_attr_servico
          ,se.dsc_descricao AS dsc_servico
          ,se.sgl_valor AS sgl_servico
          ,g.fk_tipo_apresentacao
          ,ap.dsc_descricao as dsc_tipo_apresentacao
          ,ap.sgl_valor as sgl_tipo_apresentacao
          ,g.fk_opt_apresentacao
          ,oa.dsc_descricao as dsc_opt_apresentacao
          ,oa.sgl_chave as sgl_opt_apresentacao
          ,g.fk_tabela_db
          ,tb.dsc_descricao AS dsc_tabela_db
          ,tb.sgl_valor AS sgl_tabela_db
          ,g.fk_glossario_form
          ,f.dsc_form as dsc_glossario_form
          ,g.fk_glossario_bloco_info
          ,bi.dsc_titulo_bloco AS dsc_glossario_bloco_info
          -- detalhes
          ,g.dsc_ref_info
          ,bi.dsc_titulo_bloco
          ,g.num_ordem
          ,g.bln_obrigatorio
          ,g.num_vlr_min
          ,g.num_vlr_max
          ,g.bln_info_ativa
          ,g.txt_comentario
          ,g.num_casas_decimais
          ,g.txt_formula_tot
          ,g.txt_formula_ag
          ,g.txt_formula_es
          ,g.txt_formula_ae
          ,g.txt_formula_rs
          ,g.txt_formula_dn
  FROM dicionario.tab_glossarios g
  JOIN public.vis_atributos_valores ni on (g.fk_attr_nat_info = ni.cod_atributos_valores) -- natureeza
  JOIN public.vis_atributos_valores fi on (g.fk_attr_fam_info = fi.cod_atributos_valores) -- familia
  JOIN public.vis_atributos_valores ai on (g.fk_attr_abr_info = ai.cod_atributos_valores) -- abrangencia
  JOIN public.vis_atributos_valores se on (g.fk_attr_servico = se.cod_atributos_valores)  -- servico
  JOIN public.vis_atributos_valores og on (g.fk_tipo_origem = og.cod_atributos_valores)  -- origem
  LEFT JOIN public.vis_atributos_valores ui on (g.fk_und_info = ui.cod_atributos_valores)  -- unidade
  JOIN public.vis_atributos_valores ap on (g.fk_tipo_apresentacao = ap.cod_atributos_valores)  -- apresentacao
  LEFT JOIN public.tab_atributos oa on (g.fk_opt_apresentacao = oa.cod_atributos)  -- opções de apresentacao
  JOIN public.vis_atributos_valores tb on (g.fk_tabela_db = tb.cod_atributos_valores)  -- tabela db
  JOIN dicionario.tab_form f on (g.fk_glossario_form = f.cod_form)                        
  JOIN dicionario.tab_bloco_info bi on (g.fk_glossario_bloco_info = bi.cod_bloco_info)
  ORDER BY num_ano_ref, fk_attr_servico, sgl_cod_info
;
CREATE INDEX dicionario_vis_glossarios_cod_glossario_idx ON dicionario.vis_glossarios (cod_glossario);
CREATE INDEX dicionario_vis_glossarios_num_ano_ref_idx ON dicionario.vis_glossarios (num_ano_ref);
CREATE INDEX dicionario_vis_glossarios_num_ano_ref_sgl_cod_info_idx ON dicionario.vis_glossarios (num_ano_ref, sgl_cod_info);
CREATE INDEX dicionario_vis_glossarios_fks_idx 
  ON dicionario.vis_glossarios (fk_attr_nat_info, fk_attr_fam_info, fk_attr_abr_info, fk_attr_servico, fk_glossario_form, fk_glossario_bloco_info, fk_tipo_origem);


-- ## FIM - GLOSSARIO ## --
--select * from dicionario.vis_glossarios

-- ## ALERTAS ## --
DROP TABLE IF EXISTS dicionario.tab_alertas CASCADE;
CREATE TABLE dicionario.tab_alertas AS
  SELECT *,
    cod_erro as cod_alerta, 
    (CASE WHEN tipo_erro = 'A' THEN 'AAA' -- [A]viso [A]gregado     [A]gua
          WHEN tipo_erro = 'B' THEN 'ADA' -- [A]viso [D]esagregado  [A]gua
          WHEN tipo_erro = 'F' THEN 'AMR' -- [A]viso [Municipal]    [R]esíduos sólidos

          WHEN tipo_erro = 'C' THEN 'EDA' -- [E]rro  [D]esagregado  [A]gua
          WHEN tipo_erro = 'E' THEN 'EAA' -- [E]rro  [A]gregado     [A]gua
          WHEN tipo_erro = 'G' THEN 'EMR' -- [E]rro  [Municipal]    [R]esíduos sólidos
    END)::char(3) as tipo,
    dsc_erro::text as dsc_alerta,
    ''::text as alerta_formula_ag,
    ''::text as alerta_formula_es,
    ''::text as alerta_formula_ae,
    ''::text as alerta_formula_rs,
    ''::text as alerta_formula_dn
  FROM snis.dsc_erro
  ORDER BY tipo_erro, cod_erro
;
ALTER TABLE dicionario.tab_alertas ADD PRIMARY KEY (cod_alerta);

DROP TABLE IF EXISTS dicionario.rel_glossario_alertas;
CREATE TABLE dicionario.rel_glossario_alertas (
  seq_glossario_alerta serial NOT NULL,
  fk_alerta varchar (6) NOT NULL,
  fk_glossario int NOT NULL,
  num_ano_ref smallint NOT NULL,

  PRIMARY KEY (seq_glossario_alerta),
  UNIQUE (fk_alerta, fk_glossario, num_ano_ref),

  FOREIGN KEY (fk_alerta)
    REFERENCES dicionario.tab_alertas (cod_alerta)
    ON DELETE RESTRICT ON UPDATE RESTRICT,

  FOREIGN KEY (fk_glossario)
    REFERENCES dicionario.tab_glossarios (cod_glossario)
    ON DELETE RESTRICT ON UPDATE RESTRICT
);

DROP VIEW IF EXISTS dicionario.vis_glossario_alertas;
CREATE VIEW dicionario.vis_glossario_alertas AS (
  SELECT a.*, g.*
  FROM dicionario.rel_glossario_alertas ga
  JOIN dicionario.vis_glossarios g ON (g.cod_glossario = ga.fk_glossario)
  JOIN dicionario.tab_alertas a ON (a.cod_alerta = ga.fk_alerta)
  WHERE ga.num_ano_ref = g.num_ano_ref
);
-- ## FIM - ALERTAS ## --

-- REFRESH MATERIALIZED VIEW dicionario.vis_glossarios
-- select * from dicionario.vis_glossarios
-- select * from dicionario.tab_glossarios