delete from tab_prestadores 
delete from tab_cod_antigo_pres
delete from rlc_modulos_prestadores
delete from tab_participacoes
delete from drenagem.tab_participacoes
delete from drenagem.tab_municipios_atendidos
update acesso.tab_usuarios set cod_prestador_fk=null 


--------------------------------------------------------------------
---------------------tabela prestador
INSERT INTO tab_prestadores(
            txt_ano_cadastro, txt_nome, txt_sigla, txt_endereco, 
            txt_bairro, txt_complemento, num_numero, num_cep, txt_site, num_inscricao_federal, 
            num_inscricao_estadual, num_inscricao_municipal, txt_observacoes, 
            dte_exclusao, txt_motivo_exclusao, txt_email_inclusao, txt_ultimo_ano_coleta, 
            cod_municipio_fk, txt_mandatario_nome, txt_mandatario_cargo, txt_mandatario_email,num_mandatario_fone,num_mandatario_fone2,num_mandatario_fax)



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
       p.psv_complemento, p.psv_numero, p.psv_cep, p.psv_site, 
replace (
replace (
replace (
replace (
replace (ins_fed, '-','')
	,'.','')
	,'/','')
	,'*','')
	,' ','') as
       ins_fed, p.ins_est, 
       p.ins_mun, p.obs, p.dt_exclusao, p.motivo_exclusao, p.nom_login, p.ultimo_ano_coleta, p.cod_mun,

c.cnt_nom, cnt_crg, cnt_email1, cnt_fone1, cnt_fone2, cnt_fax
  FROM snis.prestador p 
  join snis.psv_contato c on (p.cod_psv=c.cod_psv and c.ano_ref='2014' )
where ins_fed is not null and ins_fed <> '' and ins_fed <> '00.000.000/0000-00'
and c.cod_tip_funcao='2'
and psv_nom ilike '%prefeitura%'


------arrumando as mascaras do cnpj
update tab_prestadores set num_inscricao_federal=
 substring(num_inscricao_federal from 1 for 2) || '.' || substring(num_inscricao_federal from 3 for 3) || '.' || substring(num_inscricao_federal from 6 for 3)
 || '/' || substring(num_inscricao_federal from 9 for 4) || '-' || substring(num_inscricao_federal from 13 for 2)


---------deleta os com a formacao maluka
delete tab_prestadores where (num_inscricao_federal not like '__.___.___/____-__' or num_inscricao_federal='00..00.0.0/00/0-00')






---------------------------------------------------------------------------------
-------------------- tabela para fazer o DE/PARA do snis e projeto

INSERT INTO tab_cod_antigo_pres(
            cod_psv,  servico ,cod_prestador_fk)

SELECT 
p.cod_psv, cod_srv
,

( select cod_prestador from tab_prestadores  where 
replace (
replace (
replace (
replace (
replace (
num_inscricao_federal, '-','')
	,'.','')
	,'/','')
	,'*','')
	,' ','') = replace (
replace (
replace (
replace (
replace (ins_fed, '-','')
	,'.','')
	,'/','')
	,'*','')
	,' ','') 

) as cod_prestador


FROM snis.prestador
 p join snis.psv_part pp on (p.cod_psv=pp.cod_psv and  ano_ref='2014') 
 
where ins_fed is not null and ins_fed <> '' and ins_fed <> '00.000.000/0000-00'
and psv_nom ilike '%prefeitura%' AND
EXISTS ( select cod_prestador from tab_prestadores  where 
replace (
replace (
replace (
replace (
replace (
num_inscricao_federal, '-','')
	,'.','')
	,'/','')
	,'*','')
	,' ','') = replace (
replace (
replace (
replace (
replace (ins_fed, '-','')
	,'.','')
	,'/','')
	,'*','')
	,' ','') 
) 


/*
update 
public.tab_cod_antigo_pres t
set 
servico=(
select cod_srv
 from snis.psv_part
 where cod_psv= t.cod_psv
 order by ano_ref desc limit 1)
*/



--------------------------------------------------------------------------------------------
------------------------ tabela modulo prestador

INSERT INTO public.rlc_modulos_prestadores(
            cod_prestador_fk, cod_modulo_fk, txt_secretaria_nome, 
            txt_secretaria_endereco, txt_secretaria_bairro, num_secretaria_numero, 
            num_secretaria_cep, txt_secretaria_site, txt_secretaria_email, 
            num_secretaria_fone, num_secretaria_fone2, num_secretaria_fax, 
            txt_encarregado_nome, txt_encarregado_cargo, txt_encarregado_email, 
            num_encarregado_fone, num_encarregado_fone2, txt_outro_nome, 
            txt_outro_cargo, txt_outro_email, num_outro_fone, num_outro_fone2, 
            txt_login_inclusao, dte_inclusao, cod_municipio_fk, txt_complemento)




select distinct on (cod_prestador_fk, cod_modulo_fk) *

from (



SELECT 
(select cod_prestador_fk from public.tab_cod_antigo_pres  where cod_psv=pp.cod_psv) as cod_prestador_fk,
(select cod_modulo from acesso.tab_modulos  where id='drenagem') as cod_modulo_fk,
pp.psv_nom, pp.psv_end, pp.psv_bairro,  pp.psv_numero, psv_cep, psv_site,
c1.cnt_email1,c1.cnt_fone1, c1.cnt_fone2, c1.cnt_fax,
c2.cnt_nom, c2.cnt_crg, c2.cnt_email1,c2.cnt_fone1, c2.cnt_fone2,
c3.cnt_nom, c3.cnt_crg, c3.cnt_email1,c3.cnt_fone1, c3.cnt_fone2, '111', current_date, pp.cod_mun,  pp.psv_complemento
  FROM 
  snis.prestador pp 
  join snis.psv_part p on pp.cod_psv=p.cod_psv 
join snis.psv_contato c1 on (p.cod_psv=c1.cod_psv and c1.cod_tip_funcao=1 and c1.ano_ref=p.ano_ref)
join snis.psv_contato c2 on (p.cod_psv=c2.cod_psv and c2.cod_tip_funcao=3 and c2.ano_ref=p.ano_ref)
join snis.psv_contato c3 on (p.cod_psv=c3.cod_psv and c3.cod_tip_funcao=4 and c3.ano_ref=p.ano_ref)
where p.ano_ref='2014' and 
ins_fed is not null and ins_fed <> '' and ins_fed <> '00.000.000/0000-00'
and psv_nom ilike '%prefeitura%' 
 ) as registros  where cod_prestador_fk is not null 

order by cod_prestador_fk





---------------------------------------------------------------------------------------------------
-------------------------------- participação drenagem
INSERT INTO drenagem.tab_participacoes(
            cod_modulo_prestador_fk, ano_ref, bln_convidado, 
            bln_publicado, cod_situacao_preenchimento_fk, bln_pesquisa, txt_email_inclusao, 
            dte_inclusao, bln_nao_resp_ano_ant, txt_observacao, 
            cod_abrangencia_fk, cod_natureza_fk)

select
distinct on (cod_modulo_prestador) 
cod_modulo_prestador,
ano_ref, 
psv_conv,
psv_pub,
sit_preenchimento,
pesquisa, 
email, 
data, 
nao_resp_ano_ant, 
psv_obs,
cod_abr, 
cod_nat
from
(
select 

(
	select cod_modulo_prestador 
	from public.rlc_modulos_prestadores
	where cod_modulo_fk		= (select cod_modulo from acesso.tab_modulos  where id='drenagem')
	and cod_prestador_fk 	= (select cod_prestador_fk from public.tab_cod_antigo_pres  where cod_psv=p.cod_psv)
) as cod_modulo_prestador ,

ano_ref, 
 
(
select 
cod_atributos_valores
from 
public.tab_atributos a 
join public.tab_atributos_valores v on a.cod_atributos=v.fk_atributos_valores_atributos_id
where sgl_chave='rlc-natureza' and sgl_valor=cod_nat
) as cod_nat,

(
select 
cod_atributos_valores
from 
public.tab_atributos a 
join public.tab_atributos_valores v on a.cod_atributos=v.fk_atributos_valores_atributos_id
where sgl_chave='rlc-abrangencia' and sgl_valor=cod_abr
) as cod_abr,


psv_conv,
psv_pub,

(
select 
cod_atributos_valores
from 
public.tab_atributos a 
join public.tab_atributos_valores v on a.cod_atributos=v.fk_atributos_valores_atributos_id
where sgl_chave='rlc-situacao_preenchimento' and sgl_valor=sit_preenchimento::char
) as sit_preenchimento,
pesquisa, 
'1' as email, 
current_date as data, 
nao_resp_ano_ant, 
psv_obs
 from  snis.prestador pp 
  join snis.psv_part p on pp.cod_psv=p.cod_psv 
 where p.ano_ref='2014' and 
 ins_fed is not null and ins_fed <> '' and ins_fed <> '00.000.000/0000-00'
and psv_nom ilike '%prefeitura%' 

)
 as registros  where cod_modulo_prestador is not null and cod_abr is not null and cod_nat is not null

