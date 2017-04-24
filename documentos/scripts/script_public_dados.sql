INSERT INTO tab_mesorregioes(
            cod_mesorregiao, cod_estado_fk, txt_nome)

SELECT cod_mesorregiao, fk_uf, nome
  FROM snis.mesorregiao;

INSERT INTO tab_microrregioes(
            cod_microrregiao, fk_mesorregiao, txt_nome)
            
SELECT cod_microrregiao, fk_mesorregiao, nome
  FROM snis.microrregiao;

INSERT INTO tab_regioes_metropolitanas(
            cod_regiao_metropolitana, txt_nome)
SELECT id, nome
  FROM snis.regiao_metropolitana;

INSERT INTO tab_regioes_geograficas(
            cod_regiao_geografica, sgl_regiao_geografica, txt_nome)

SELECT cod_reg_geo::int, sgl_reg_geo, nom_reg_geo
  FROM snis.reg_geo;

INSERT INTO tab_estados(
            sgl_estado, cod_estado, txt_nome, cod_cpt_est, qtd_mun_est, vlr_taxa_hab_dom, 
            cod_regiao_geografica)

SELECT sgl_est,  cod_est, nom_est, cod_cpt_est, qtd_mun_est, 
       tx_hab_dom, cod_reg_geo::int
  FROM snis.estado;

INSERT INTO tab_municipios(
            cod_municipio, txt_nome, sgl_estado_fk, bln_indicador_capital, 
            cod_microrregiao_fk, cod_ibge, cod_regiao_metropolitana_fk)
    

SELECT cod_mun, nom_mun, sgl_est, ind_capital, fk_microrregiao, cod_ibge, 
       fk_regiao_metropolitana
  FROM snis.municipio;

-- Prestadores com CNPJ
INSERT INTO tab_prestadores(
            txt_ano_cadastro, txt_nome, txt_sigla, txt_endereco, 
            txt_bairro, txt_complemento, num_numero, num_cep, txt_site, num_inscricao_federal, 
            num_inscricao_estadual, num_inscricao_municipal, txt_observacoes, 
            dte_exclusao, txt_motivo_exclusao, txt_login_inclusao, txt_ultimo_ano_coleta, 
            cod_municipio_fk)
SELECT distinct on (
replace (
replace (
replace (
replace (
replace (ins_fed, '-','')
	,'.','')
	,'/','')
	,'*','')
	,' ','') 
) p.ano_cad, p.psv_nom, p.psv_sgl, p.psv_end, p.psv_bairro, 
       p.psv_complemento, p.psv_numero, p.psv_cep, p.psv_site, p.ins_fed, p.ins_est, 
       p.ins_mun, p.obs, p.dt_exclusao, p.motivo_exclusao, p.nom_login, p.ultimo_ano_coleta, p.cod_mun
  FROM snis.prestador p
where ins_fed is not null and ins_fed <> '' and ins_fed <> '00.000.000/0000-00';

-- Prestadores sem CNPJ
INSERT INTO tab_prestadores(
            txt_ano_cadastro, txt_nome, txt_sigla, txt_endereco, 
            txt_bairro, txt_complemento, num_numero, num_cep, txt_site, num_inscricao_federal, 
            num_inscricao_estadual, num_inscricao_municipal, txt_observacoes, 
            dte_exclusao, txt_motivo_exclusao, txt_login_inclusao, txt_ultimo_ano_coleta, 
            cod_municipio_fk)
SELECT distinct on (

replace (
replace (
replace (
replace (trim(upper(p.psv_nom)), ' ','')
	,'´','')
,'''','')
,',','')


, p.cod_mun) p.ano_cad, p.psv_nom, p.psv_sgl, p.psv_end, p.psv_bairro, 
       p.psv_complemento, p.psv_numero, p.psv_cep, p.psv_site, p.ins_fed, p.ins_est, 
       p.ins_mun, p.obs, p.dt_exclusao, p.motivo_exclusao, p.nom_login, p.ultimo_ano_coleta, p.cod_mun
  FROM snis.prestador p
where (ins_fed is null or ins_fed = '' or ins_fed = '00.000.000/0000-00') and 
not exists (select cod_prestador from tab_prestadores where 

replace (
replace (
replace (
replace (trim(upper(txt_nome)), ' ','')
	,'´','')
,'''','')
,',','')
=
replace (
replace (
replace (
replace (trim(upper(p.psv_nom)), ' ','')
	,'´','')
,'''','')
,',','')

and p.cod_mun=cod_municipio_fk);