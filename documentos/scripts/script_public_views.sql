-- View: public.vis_consulta_prestadores

-- DROP VIEW public.vis_consulta_prestadores;

CREATE OR REPLACE VIEW public.vis_consulta_prestadores AS 
 SELECT mo.cod_modulo,
    mo.id AS id_modulo,
    mo.txt_nome AS nome_modulo,
    pa.cod_participacao,
    pa.ano_ref,
    pa.bln_pesquisa,
        CASE
            WHEN pa.bln_pesquisa = '2' OR pa.bln_pesquisa = '3' THEN 'S'
            ELSE 'N'
        END AS valor_pesquisa,
        CASE
            WHEN pa.bln_pesquisa = '2'::bpchar OR pa.bln_pesquisa = '3'::bpchar THEN 'Sim'::text
            ELSE 'Não'::text
        END AS dsc_pesquisa,
        CASE
            WHEN pa.bln_nao_resp_ano_ant = '1' THEN 'S'
            ELSE 'N'
        END AS bln_nao_resp_ano_ant,    
        CASE
            WHEN pa.bln_nao_resp_ano_ant = '1'::bpchar THEN 'Sim'::text
            ELSE 'Não'::text
        END AS dsc_nao_resp_ano_ant,
    pa.cod_abrangencia_fk AS cod_abrangencia,
    pa.valor_abrangencia,
    pa.dsc_abrangencia,
    pa.cod_situacao_preenchimento_fk AS cod_situacao_preenchimento,
    pa.valor_situacao_preenchimento,
    pa.dsc_situacao_preenchimento,
    pa.cod_natureza_fk AS cod_natureza,
    pa.valor_natureza,
    pa.dsc_natureza,
    rg.cod_regiao_geografica,
    rg.sgl_regiao_geografica,
    rg.txt_nome AS nome_regiao_geografica,
    es.cod_estado,
    es.sgl_estado,
    es.txt_nome AS nome_estado,
    mu.cod_municipio,
    mu.txt_nome AS nome_municipio,
        CASE
            WHEN mu.bln_indicador_capital = TRUE THEN 'S'
            ELSE 'N'
        END AS bln_indicador_capital,                        
        CASE
            WHEN mu.bln_indicador_capital = true THEN 'Sim'::text
            ELSE 'Não'::text
        END AS dsc_indicador_capital,
    mu.cod_regiao_metropolitana_fk,
    rm.txt_nome AS nome_regiao_metropolitana,
    pr.cod_prestador,
    pr.txt_sigla AS sgl_prestador,
    pr.txt_nome AS nome_prestador,
    pr.txt_tipo_prestador,
    avtp.dsc_descricao AS dsc_tipo_prestador
   FROM tab_regioes_geograficas rg
     JOIN tab_estados es ON rg.cod_regiao_geografica = es.cod_regiao_geografica
     JOIN tab_municipios mu ON es.sgl_estado = mu.sgl_estado_fk
     LEFT JOIN tab_regioes_metropolitanas rm ON mu.cod_regiao_metropolitana_fk = rm.cod_regiao_metropolitana
     JOIN tab_prestadores pr ON mu.cod_municipio = pr.cod_municipio_fk
     JOIN tab_atributos_valores avtp ON pr.txt_tipo_prestador = avtp.sgl_valor::bpchar AND avtp.fk_atributos_valores_atributos_id = 15
     JOIN rlc_modulos_prestadores mp ON pr.cod_prestador = mp.cod_prestador_fk
     JOIN acesso.tab_modulos mo ON mp.cod_modulo_fk = mo.cod_modulo
     JOIN vis_participacoes pa ON mp.cod_modulo_prestador = pa.cod_modulo_prestador_fk
  ORDER BY rg.txt_nome, es.txt_nome, mu.txt_nome, pr.txt_nome;

ALTER TABLE public.vis_consulta_prestadores
  OWNER TO snsa;

----------------------------------------------------------------

-- View: public.vis_participacoes

-- DROP VIEW public.vis_participacoes;

CREATE OR REPLACE VIEW public.vis_participacoes AS 
    select distinct pa.*
        ,avsp.sgl_valor as valor_situacao_preenchimento
        ,avsp.dsc_descricao as dsc_situacao_preenchimento
        ,avab.sgl_valor as valor_abrangencia
        ,avab.dsc_descricao as dsc_abrangencia
        ,avna.sgl_valor as valor_natureza
        ,avna.dsc_descricao as dsc_natureza
    from drenagem.tab_participacoes pa
    inner join public.tab_atributos_valores avsp on pa.cod_situacao_preenchimento_fk  = avsp.cod_atributos_valores
    inner join public.tab_atributos_valores avab on pa.cod_abrangencia_fk  = avab.cod_atributos_valores
    inner join public.tab_atributos_valores avna on pa.cod_natureza_fk  = avna.cod_atributos_valores;

ALTER TABLE public.vis_consulta_prestadores
    OWNER TO snsa;  