DROP TABLE if exists dicionario.tab_avisos_erros cascade;
CREATE TABLE dicionario.tab_avisos_erros
(
  cod_aviso_erro serial NOT NULL, -- Código
  fk_attr_formulario integer NOT NULL, -- Formulário
  fk_modulo integer NOT NULL, -- Módulo
  fk_attr_tipo_aviso_erro integer NOT NULL, -- Tipo

  num_ano_ref smallint not null,
  txt_codigo character varying(6) NOT NULL, -- Código
  txt_descricao text NOT NULL, -- Descrição
  txt_formula character varying(2000) NOT NULL, -- Fórmula
  bln_ativo boolean NOT NULL DEFAULT true, -- Ativo
  
  txt_login_inclusao character varying(150), -- Usuário da Inclusão
  dte_inclusao timestamp without time zone, -- Data da Inclusão
  dte_alteracao timestamp without time zone, -- Data da Alteração
  dte_exclusao timestamp without time zone, -- Data da Exclusão
  
  CONSTRAINT pk_avisos_erros_cod_aviso_erro PRIMARY KEY (cod_aviso_erro),
  UNIQUE (num_ano_ref, txt_codigo),
  CONSTRAINT tab_avisos_erros_fk_attr_formulario FOREIGN KEY (fk_attr_formulario)
      REFERENCES dicionario.tab_form (cod_form) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT tab_avisos_erros_fk_attr_tipo_aviso_erro FOREIGN KEY (fk_attr_tipo_aviso_erro)
      REFERENCES public.tab_atributos_valores (cod_atributos_valores) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT tab_avisos_erros_fk_modulo FOREIGN KEY (fk_modulo)
      REFERENCES acesso.tab_modulos (cod_modulo) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
);

DROP TABLE if exists dicionario.rlc_avisos_erros_glossarios;
CREATE TABLE dicionario.rlc_avisos_erros_glossarios
(
  cod_aviso_erro_glossario serial NOT NULL, -- Código
  fk_aviso_erro integer NOT NULL, -- Aviso ou Erro
  fk_glossario integer NOT NULL, -- Glossário
  
  txt_login_inclusao character varying(150), -- Usuário da Inclusão
  dte_inclusao timestamp without time zone, -- Data da Inclusão
  dte_alteracao timestamp without time zone, -- Data da Alteração
  dte_exclusao timestamp without time zone, -- Data da Exclusão

  CONSTRAINT pk_avisos_erros_glossarios_cod_aviso_erro_glossario PRIMARY KEY (cod_aviso_erro_glossario),
  CONSTRAINT rlc_avisos_erros_glossarios_fk_aviso_erro FOREIGN KEY (fk_aviso_erro)
      REFERENCES dicionario.tab_avisos_erros (cod_aviso_erro) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT rlc_avisos_erros_glossarios_fk_glossario FOREIGN KEY (fk_glossario)
      REFERENCES dicionario.tab_glossarios (cod_glossario) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
);



drop materialized view if exists dicionario.vis_avisos_erros_campos;
create materialized view dicionario.vis_avisos_erros_campos as
select distinct
  cod_aviso_erro
  ,g.sgl_cod_info
  ,txt_descricao
  ,txt_formula
  ,bln_ativo
  ,fk_attr_tipo_aviso_erro
  ,txt_codigo
  ,fk_attr_formulario
  ,f.dsc_form
  ,fk_modulo
  ,m.dsc_modulo
  ,reg.dte_exclusao
from dicionario.tab_avisos_erros ae
join dicionario.tab_form f on (ae.fk_attr_formulario = f.cod_form)
join acesso.tab_modulos m on (ae.fk_modulo = m.cod_modulo)
join dicionario.rlc_avisos_erros_glossarios reg on (cod_aviso_erro = reg.fk_aviso_erro)
join dicionario.vis_glossarios g on (reg.fk_glossario = g.cod_glossario)
where true
  and g.num_ano_ref = ae.num_ano_ref
  and reg.dte_exclusao is null
;

drop materialized view if exists dicionario.vis_avisos_erros;
create materialized view dicionario.vis_avisos_erros as
select 
  cod_aviso_erro
  ,txt_descricao
  ,txt_formula
  ,bln_ativo
  ,fk_attr_tipo_aviso_erro
  ,txt_codigo
  ,fk_attr_formulario
  ,f.dsc_form
  ,fk_modulo
  ,m.dsc_modulo

from dicionario.tab_avisos_erros ae
join dicionario.tab_form f on (ae.fk_attr_formulario = f.cod_form)
join acesso.tab_modulos m on (ae.fk_modulo = m.cod_modulo)
;
/*
insert into dicionario.tab_avisos_erros (num_ano_ref, fk_attr_tipo_aviso_erro, fk_attr_formulario, fk_modulo, txt_codigo, txt_descricao, txt_formula) values 
(2014, 221, 5, 2, 'ECB002', 'Ocorre erro quando o campo CB006 for SIM e o campo CB007 for NÃO', '(CB006 = SIM) e (CB007 = NÃO)');

insert into dicionario.rlc_avisos_erros_glossarios (fk_aviso_erro, fk_glossario) values (1,1930);
insert into dicionario.rlc_avisos_erros_glossarios (fk_aviso_erro, fk_glossario) values (1,1931);

refresh materialized view dicionario.vis_avisos_erros;
refresh materialized view dicionario.vis_avisos_erros_campos;
*/