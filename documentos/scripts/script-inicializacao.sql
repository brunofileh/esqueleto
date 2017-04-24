TRUNCATE TABLE drenagem.rlc_outras_entidades;
TRUNCATE TABLE drenagem.tab_participacoes;
TRUNCATE TABLE drenagem.tab_campo_multi_opcoes;
TRUNCATE TABLE drenagem.tab_campos_auxiliares;
TRUNCATE TABLE drenagem.tab_hist_pre;
TRUNCATE TABLE drenagem.tab_gerais;
TRUNCATE TABLE drenagem.tab_gerais_bacias_hidrograficas;
TRUNCATE TABLE drenagem.tab_gerais_rios;
TRUNCATE TABLE drenagem.tab_cobrancas;
TRUNCATE TABLE drenagem.tab_financeiros;
TRUNCATE TABLE drenagem.tab_infraestruturas;
TRUNCATE TABLE drenagem.tab_infraestruturas_bacias;
TRUNCATE TABLE drenagem.tab_infraestruturas_outras_areas;
TRUNCATE TABLE drenagem.tab_infraestruturas_parques;
TRUNCATE TABLE drenagem.tab_retencao_vazoes;
TRUNCATE TABLE drenagem.tab_corpos_receptores;
TRUNCATE TABLE drenagem.tab_parques_lineares;
TRUNCATE TABLE drenagem.tab_operacionais;
TRUNCATE TABLE drenagem.tab_operacionais_intervencoes;
TRUNCATE TABLE drenagem.tab_riscos;
TRUNCATE TABLE drenagem.tab_avaliacoes_reacoes;
TRUNCATE TABLE public.tab_contatos;
TRUNCATE TABLE acesso.tab_sessao_php;

ALTER SEQUENCE public.campo_multi_opcoes_seq RESTART 1;
ALTER SEQUENCE drenagem.tab_participacoes2_cod_participacao_seq RESTART 1;
ALTER SEQUENCE drenagem.tab_infraestruturas_outras_ar_cod_infraestrutura_outra_area_seq RESTART 1;
ALTER SEQUENCE drenagem.tab_operacionais_interevencoes_cod_operacional_interevencao_seq RESTART 1;

UPDATE dicionario.tab_avisos_erros SET num_ano_ref = 2015 WHERE num_ano_ref = 2014;
UPDATE dicionario.tab_glossarios SET num_ano_ref = 2015 WHERE num_ano_ref = 2014;

REFRESH MATERIALIZE VIEW dicionario.vis_glossarios;
REFRESH MATERIALIZE VIEW dicionario.vis_avisos_erros;
REFRESH MATERIALIZE VIEW dicionario.vis_avisos_erros_campos;
REFRESH MATERIALIZE VIEW public.vis_atributos_valores;
