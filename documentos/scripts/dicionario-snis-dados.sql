
INSERT INTO public.tab_atributos (dsc_descricao, sgl_chave) VALUES ('Tipo de operação da unidade', 'tipo-operacao-unidade');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 1, 'Lixão');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 2, 'Aterro controlado');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 3, 'Aterro sanitário');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 4, 'Vala especifica de RSS');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 5, 'Aterro industrial');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 6, 'Unidade de triagem (galpão ou usina)');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 7, 'Unidade de compostagem (pátio ou usina)');


-- insere as opções de Sim/Não
INSERT INTO public.tab_atributos (dsc_descricao, sgl_chave) VALUES ('Opções de Sim/Não', 'opt-sim-nao');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'S', '[S] Sim');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'N', '[N] Não');

-- insere as opções dos tipos de serviços prestados
INSERT INTO public.tab_atributos (dsc_descricao, sgl_chave) VALUES ('Serviço prestado', 'rlc-servico-prestado');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'AG', '[AG] Abastecimento de água');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'ES', '[ES] Esgotamento sanitário');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'AE', '[AE] Abastecimento de água e esgotamento sanitário');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'RS', '[RS] Coleta e manejo de resíduos sólidos');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'DN', '[DN] Drenagem e manejo de águas pluviais');

-- insere as opções dos tipos de abrangência de informações
INSERT INTO public.tab_atributos (dsc_descricao, sgl_chave) VALUES ('Abrangência da informação', 'rlc-abrangencia-informacao');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'AE-A', '[AE-A] Dados agregados de água e esgotos');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'AE-D', '[AE-D] Dados desagregadas de água e esgotos');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'AE-M', '[AE-M] Dados municipais de água e esgotos');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'RS-M', '[RS-M] Dados municipais de resíduos sólidos');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'DN-A', '[DN-A] Dados agregados de drenagem e manejo de águas pluviais');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'DN-D', '[DN-D] Dados desagregados de drenagem e manejo de águas pluviais');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'DN-M', '[DN-M] Dados municipais de drenagem e manejo de águas pluviais');

-- insere as opções dos tipos de natureza da informação
INSERT INTO public.tab_atributos (dsc_descricao, sgl_chave) VALUES ('Natureza da informação', 'rlc-natureza-informacao');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'AE', '[AE] Água e esgotos');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'RS', '[RS] Resíduos sólidos');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'DN', '[DN] Drenagem');

-- insere as opções dos tipos de família da informação
INSERT INTO public.tab_atributos (dsc_descricao, sgl_chave) VALUES ('Família da informação', 'rlc-familia-informacao');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), '1', '[DN] Dados Financeiros de Drenagem');

-- insere as opções dos tipos de origem da informação
INSERT INTO public.tab_atributos (dsc_descricao, sgl_chave) VALUES ('Tipo de origem da informação', 'rlc-tipo-origem-informacao');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'D', '[D] Dado coletado');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'I', '[I] Indicador');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'T', '[T] Totalizador');

-- tipo de unidade da informação
INSERT INTO public.tab_atributos (dsc_descricao, sgl_chave) VALUES ('Tipo de unidade da informação', 'rlc-tipo-unidade-informacao');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'ton.', '[ton] Tonelada');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'R$', '[R$] Reais');

-- tipo de apresentação do campo
INSERT INTO public.tab_atributos (dsc_descricao, sgl_chave) VALUES ('Tipo de apresentação do campo', 'rlc-tipo-apresentacao');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'input:text', '[input:text] Texto curto em linha única');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'input:email', '[input:email] Endereço de e-mail');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'input:numeric', '[input:numeric] Campo numérico');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'input:phone', '[input:phone] Telefone');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'input:date-picker', '[input:date-picker] Seleção de data');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'textarea:p', '[textarea:p] Texto curto em múltiplas linhas');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'textarea:g', '[textarea:g] Texto longo em múltiplas linhas');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'radiolist', '[radiolist] Lista para seleção única formato botôes');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'dropdownlist', '[dropdownlist] Lista para seleção única formato cortina');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'checkboxlist', '[checkboxlist] Caixas de múltipla seleção');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'multiselect', '[multiselect] Cortina de múltipla seleção');

-- Relação de tabelas da coleta de dados
INSERT INTO public.tab_atributos (dsc_descricao, sgl_chave) VALUES ('Relação de tabelas da coleta de dados', 'rlc-tabelas-coleta');
INSERT INTO public.tab_atributos_valores (fk_atributos_valores_atributos_id, sgl_valor, dsc_descricao) VALUES ((select last_value from tab_atributos_cod_atributos_seq), 'drenagem.tab_dn_financeiros', '[DN] Tabela de dados agregados financeiros de drenagem');

-- atualiza view
REFRESH MATERIALIZED VIEW public.vis_atributos_valores;

-- FORMULARIOS --
INSERT INTO dicionario.tab_form (cod_tipo_servico, dsc_form, dsc_det_form) VALUES (
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-servico-prestado' AND sgl_valor = 'DN'), 
  '[DN] Financeiros', 
  'Formulário de dados financeiros de drenagem'
);

-- BLOCOS --
INSERT INTO dicionario.tab_bloco_info (dsc_titulo_bloco) VALUES ('[DN] Bloco 1');
INSERT INTO dicionario.tab_bloco_info (dsc_titulo_bloco) VALUES ('[DN] Bloco 2');

-- select * from vis_atributos_valores
-- select * from dicionario.vis_glossarios
/*
INSERT INTO dicionario.tab_glossarios (
  fk_attr_nat_info,
  fk_attr_fam_info,
  fk_attr_abr_info,
  fk_attr_servico,
  fk_glossario_form,
  fk_glossario_bloco_info,
  num_ano_ref,
  sgl_cod_info,
  dsc_nom_info,
  dsc_det_info,
  fk_und_info,
  fk_origem,
  dsc_ref_info,
  num_ordem,
  dsc_tabela_db,
  bln_coletar_info,
  dsc_apresentacao
) 
VALUES
(
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-natureza-informacao' AND sgl_valor = 'DN'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-familia-informacao' AND sgl_valor = '1'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-abrangencia-informacao' AND sgl_valor = 'DN-A'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-servico-prestado' AND sgl_valor = 'DN'),
  1,
  1,
  '2014',
  'FN201',
  'A Prefeitura cobra pelos serviços de coleta regular, transporte e destinação final de RSU',
  'Existência de cobrança pelos serviços regulares de manejo de RSU, notadamente pela coleta de resíduos domiciliares.',
  'S/N',
  'FN205, X000, X026',
  1,
  'drenagem.tab_dn_financeiros',
  't',
  'input:text'
);

INSERT INTO dicionario.tab_glossarios (
  fk_attr_nat_info,
  fk_attr_fam_info,
  fk_attr_abr_info,
  fk_attr_servico,
  fk_glossario_form,
  fk_glossario_bloco_info,
  num_ano_ref,
  sgl_cod_info,
  dsc_nom_info,
  dsc_det_info,
  fk_und_info,
  fk_origem,
  dsc_ref_info,
  num_ordem,
  dsc_tabela_db,
  bln_coletar_info,
  txt_comentario,
  dsc_apresentacao
) 
VALUES
(
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-natureza-informacao' AND sgl_valor = 'DN'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-familia-informacao' AND sgl_valor = '1'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-abrangencia-informacao' AND sgl_valor = 'DN-A'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-servico-prestado' AND sgl_valor = 'DN'),
  1,
  1,
  '2014',
  'FN202',
  'A Prefeitura cobra pelos serviços de coleta regular, transporte e destinação final de RSU',
  'Existência de cobrança pelos serviços regulares de manejo de RSU, notadamente pela coleta de resíduos domiciliares.',
  'S/N',
  'FN205, X000, X026',
  1,
  'drenagem.tab_dn_financeiros',
  't',
  'comentário',
  'input:email'
);

INSERT INTO dicionario.tab_glossarios (
  fk_attr_nat_info,
  fk_attr_fam_info,
  fk_attr_abr_info,
  fk_attr_servico,
  fk_glossario_form,
  fk_glossario_bloco_info,
  num_ano_ref,
  sgl_cod_info,
  dsc_nom_info,
  dsc_det_info,
  fk_und_info,
  fk_origem,
  dsc_ref_info,
  num_ordem,
  dsc_tabela_db,
  bln_coletar_info,
  txt_comentario,
  dsc_apresentacao
) 
VALUES
(
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-natureza-informacao' AND sgl_valor = 'DN'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-familia-informacao' AND sgl_valor = '1'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-abrangencia-informacao' AND sgl_valor = 'DN-A'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-servico-prestado' AND sgl_valor = 'DN'),
  1,
  1,
  '2014',
  'FN203',
  'A Prefeitura cobra pelos serviços de coleta regular, transporte e destinação final de RSU',
  'Existência de cobrança pelos serviços regulares de manejo de RSU, notadamente pela coleta de resíduos domiciliares.',
  'S/N',
  'FN205, X000, X026',
  1,
  'drenagem.tab_dn_financeiros',
  't',
  'comentário',
  'input:numeric'
);

INSERT INTO dicionario.tab_glossarios (
  fk_attr_nat_info,
  fk_attr_fam_info,
  fk_attr_abr_info,
  fk_attr_servico,
  fk_glossario_form,
  fk_glossario_bloco_info,
  num_ano_ref,
  sgl_cod_info,
  dsc_nom_info,
  dsc_det_info,
  fk_und_info,
  fk_origem,
  dsc_ref_info,
  num_ordem,
  dsc_tabela_db,
  bln_coletar_info,
  txt_comentario,
  dsc_apresentacao
) 
VALUES
(
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-natureza-informacao' AND sgl_valor = 'DN'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-familia-informacao' AND sgl_valor = '1'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-abrangencia-informacao' AND sgl_valor = 'DN-A'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-servico-prestado' AND sgl_valor = 'DN'),
  1,
  1,
  '2014',
  'FN204',
  'A Prefeitura cobra pelos serviços de coleta regular, transporte e destinação final de RSU',
  'Existência de cobrança pelos serviços regulares de manejo de RSU, notadamente pela coleta de resíduos domiciliares.',
  'S/N',
  'FN205, X000, X026',
  1,
  'drenagem.tab_dn_financeiros',
  't',
  'comentário',
  'input:phone'
);

INSERT INTO dicionario.tab_glossarios (
  fk_attr_nat_info,
  fk_attr_fam_info,
  fk_attr_abr_info,
  fk_attr_servico,
  fk_glossario_form,
  fk_glossario_bloco_info,
  num_ano_ref,
  sgl_cod_info,
  dsc_nom_info,
  dsc_det_info,
  fk_und_info,
  fk_origem,
  dsc_ref_info,
  num_ordem,
  dsc_tabela_db,
  bln_coletar_info,
  txt_comentario,
  dsc_apresentacao
) 
VALUES
(
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-natureza-informacao' AND sgl_valor = 'DN'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-familia-informacao' AND sgl_valor = '1'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-abrangencia-informacao' AND sgl_valor = 'DN-A'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-servico-prestado' AND sgl_valor = 'DN'),
  1,
  1,
  '2014',
  'FN205',
  'Receita operacional direta de água',
  'Valor faturado anual decorrente da prestação do serviço de abastecimento de água, resultante exclusivamente da aplicação de tarifas e/ou taxas, excluídos os valores decorrentes da venda de água exportada no atacado.',
  'R$',
  'FN202',
  2,
  'drenagem.tab_dn_financeiros',
  't',
  'outro comentário',
  'radiolist:tipo-operacao-unidade'
);

INSERT INTO dicionario.tab_glossarios (
  fk_attr_nat_info,
  fk_attr_fam_info,
  fk_attr_abr_info,
  fk_attr_servico,
  fk_glossario_form,
  fk_glossario_bloco_info,
  num_ano_ref,
  sgl_cod_info,
  dsc_nom_info,
  dsc_det_info,
  fk_und_info,
  fk_origem,
  dsc_ref_info,
  num_ordem,
  dsc_tabela_db,
  bln_coletar_info,
  txt_comentario,
  dsc_apresentacao
) 
VALUES
(
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-natureza-informacao' AND sgl_valor = 'DN'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-familia-informacao' AND sgl_valor = '1'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-abrangencia-informacao' AND sgl_valor = 'DN-A'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-servico-prestado' AND sgl_valor = 'DN'),
  1,
  1,
  '2014',
  'FN206',
  'Receita operacional direta de água',
  'Valor faturado anual decorrente da prestação do serviço de abastecimento de água, resultante exclusivamente da aplicação de tarifas e/ou taxas, excluídos os valores decorrentes da venda de água exportada no atacado.',
  'R$',
  'FN203',
  2,
  'drenagem.tab_dn_financeiros',
  't',
  'outro comentário',
  'dropdownlist:tipo-operacao-unidade'
);

INSERT INTO dicionario.tab_glossarios (
  fk_attr_nat_info,
  fk_attr_fam_info,
  fk_attr_abr_info,
  fk_attr_servico,
  fk_glossario_form,
  fk_glossario_bloco_info,
  num_ano_ref,
  sgl_cod_info,
  dsc_nom_info,
  dsc_det_info,
  fk_und_info,
  fk_origem,
  dsc_ref_info,
  num_ordem,
  dsc_tabela_db,
  bln_coletar_info,
  txt_comentario,
  dsc_apresentacao
) 
VALUES
(
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-natureza-informacao' AND sgl_valor = 'DN'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-familia-informacao' AND sgl_valor = '1'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-abrangencia-informacao' AND sgl_valor = 'DN-A'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-servico-prestado' AND sgl_valor = 'DN'),
  1,
  1,
  '2014',
  'FN207',
  'Receita operacional direta de água',
  'Valor faturado anual decorrente da prestação do serviço de abastecimento de água, resultante exclusivamente da aplicação de tarifas e/ou taxas, excluídos os valores decorrentes da venda de água exportada no atacado.',
  'R$',
  'FN204',
  2,
  'drenagem.tab_dn_financeiros',
  't',
  'outro comentário',
  'checkboxlist:tipo-operacao-unidade'
);

INSERT INTO dicionario.tab_glossarios (
  fk_attr_nat_info,
  fk_attr_fam_info,
  fk_attr_abr_info,
  fk_attr_servico,
  fk_glossario_form,
  fk_glossario_bloco_info,
  num_ano_ref,
  sgl_cod_info,
  dsc_nom_info,
  dsc_det_info,
  fk_und_info,
  fk_origem,
  dsc_ref_info,
  num_ordem,
  dsc_tabela_db,
  bln_coletar_info,
  txt_comentario,
  dsc_apresentacao
) 
VALUES
(
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-natureza-informacao' AND sgl_valor = 'DN'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-familia-informacao' AND sgl_valor = '1'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-abrangencia-informacao' AND sgl_valor = 'DN-A'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-servico-prestado' AND sgl_valor = 'DN'),
  1,
  1,
  '2014',
  'FN208',
  'Receita operacional direta de água',
  'Valor faturado anual decorrente da prestação do serviço de abastecimento de água, resultante exclusivamente da aplicação de tarifas e/ou taxas, excluídos os valores decorrentes da venda de água exportada no atacado.',
  'R$',
  'FN205',
  2,
  'drenagem.tab_dn_financeiros',
  't',
  'outro comentário',
  'multiselect:tipo-operacao-unidade'
);

INSERT INTO dicionario.tab_glossarios (
  fk_attr_nat_info,
  fk_attr_fam_info,
  fk_attr_abr_info,
  fk_attr_servico,
  fk_glossario_form,
  fk_glossario_bloco_info,
  num_ano_ref,
  sgl_cod_info,
  dsc_nom_info,
  dsc_det_info,
  fk_und_info,
  fk_origem,
  dsc_ref_info,
  num_ordem,
  dsc_tabela_db,
  bln_coletar_info,
  txt_comentario,
  dsc_apresentacao
) 
VALUES
(
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-natureza-informacao' AND sgl_valor = 'DN'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-familia-informacao' AND sgl_valor = '1'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-abrangencia-informacao' AND sgl_valor = 'DN-A'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-servico-prestado' AND sgl_valor = 'DN'),
  1,
  1,
  '2014',
  'FN209',
  'Receita operacional direta de água',
  'Valor faturado anual decorrente da prestação do serviço de abastecimento de água, resultante exclusivamente da aplicação de tarifas e/ou taxas, excluídos os valores decorrentes da venda de água exportada no atacado.',
  '',
  'FN205',
  2,
  'drenagem.tab_dn_financeiros',
  't',
  'outro comentário',
  'input:date-picker'
);

INSERT INTO dicionario.tab_glossarios (
  fk_attr_nat_info,
  fk_attr_fam_info,
  fk_attr_abr_info,
  fk_attr_servico,
  fk_glossario_form,
  fk_glossario_bloco_info,
  num_ano_ref,
  sgl_cod_info,
  dsc_nom_info,
  dsc_det_info,
  fk_und_info,
  fk_origem,
  dsc_ref_info,
  num_ordem,
  dsc_tabela_db,
  bln_coletar_info,
  txt_comentario,
  dsc_apresentacao
) 
VALUES
(
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-natureza-informacao' AND sgl_valor = 'DN'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-familia-informacao' AND sgl_valor = '1'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-abrangencia-informacao' AND sgl_valor = 'DN-A'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-servico-prestado' AND sgl_valor = 'DN'),
  1,
  1,
  '2014',
  'FN210',
  'Receita operacional direta de água',
  'Valor faturado anual decorrente da prestação do serviço de abastecimento de água, resultante exclusivamente da aplicação de tarifas e/ou taxas, excluídos os valores decorrentes da venda de água exportada no atacado.',
  '',
  'FN205',
  2,
  'drenagem.tab_dn_financeiros',
  't',
  'outro comentário',
  'textarea:p'
);

INSERT INTO dicionario.tab_glossarios (
  fk_attr_nat_info,
  fk_attr_fam_info,
  fk_attr_abr_info,
  fk_attr_servico,
  fk_glossario_form,
  fk_glossario_bloco_info,
  num_ano_ref,
  sgl_cod_info,
  dsc_nom_info,
  dsc_det_info,
  fk_und_info,
  fk_origem,
  dsc_ref_info,
  num_ordem,
  dsc_tabela_db,
  bln_coletar_info,
  txt_comentario,
  dsc_apresentacao
) 
VALUES
(
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-natureza-informacao' AND sgl_valor = 'DN'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-familia-informacao' AND sgl_valor = '1'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-abrangencia-informacao' AND sgl_valor = 'DN-A'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-servico-prestado' AND sgl_valor = 'DN'),
  1,
  1,
  '2014',
  'FN211',
  'Receita operacional direta de água',
  'Valor faturado anual decorrente da prestação do serviço de abastecimento de água, resultante exclusivamente da aplicação de tarifas e/ou taxas, excluídos os valores decorrentes da venda de água exportada no atacado.',
  '',
  'FN205',
  2,
  'drenagem.tab_dn_financeiros',
  't',
  'outro comentário',
  'textarea:g'
);

INSERT INTO dicionario.tab_glossarios (
  fk_attr_nat_info,
  fk_attr_fam_info,
  fk_attr_abr_info,
  fk_attr_servico,
  fk_glossario_form,
  fk_glossario_bloco_info,
  num_ano_ref,
  sgl_cod_info,
  dsc_nom_info,
  dsc_det_info,
  dsc_und_info,
  dsc_ref_info,
  num_ordem,
  dsc_tabela_db,
  bln_coletar_info,
  txt_comentario,
  dsc_apresentacao
) 
VALUES
(
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-natureza-informacao' AND sgl_valor = 'RS'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-familia-informacao' AND sgl_valor = '1'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-abrangencia-informacao' AND sgl_valor = 'RS-M'),
  (SELECT cod_atributos_valores FROM vis_atributos_valores WHERE sgl_chave = 'rlc-servico-prestado' AND sgl_valor = 'RS'),
  1,
  1,
  '2014',
  'RS001',
  'Receita operacional direta de água',
  'Valor faturado anual decorrente da prestação do serviço de abastecimento de água, resultante exclusivamente da aplicação de tarifas e/ou taxas, excluídos os valores decorrentes da venda de água exportada no atacado.',
  '',
  '',
  2,
  'rsolidos.tab_rs_financeiros',
  't',
  '',
  'input:text'
);

-- atualiza view
REFRESH MATERIALIZED VIEW dicionario.vis_glossarios;
-- select * from dicionario.vis_glossarios
-- select * from dicionario.tab_glossarios
-- select * from vis_atributos_valores
*/
--commit;

-- select * from vis_atributos_valores
-- select * from tab_atributos