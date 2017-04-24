select *
from (
	select mu.cod_ibge
	    , mu.txt_nome as municipio
	    , mu.sgl_estado_fk || ' - ' || es.txt_nome as uf
            
	    , case
		when (ar.ar001 != null and ar.ar001c = null and (ar.ar001 = 380 or ar.ar001 = 381)) then 'ERRO'
		else ''
	      end as VAL_EAR001

	    , case
		when (ar.ar002 != null and ar.ar002c = null and (ar.ar002 = 380 or ar.ar002 = 381)) then 'ERRO'
		else ''
	      end as VAL_EAR002

	    , case
		when (ar.ar003 != null and ar.ar003c = null and (ar.ar003 = 380 or ar.ar003 = 381)) then 'ERRO'
		else ''
	      end as VAL_EAR003

	    , case
		when (ar.ar004 != null and ar.ar004c = null and (ar.ar004 = 380 or ar.ar004 = 381)) then 'ERRO'
		else ''
	      end as VAL_EAR004

	    , case
		when (ar.ar005 != null and ar.ar005c = null and (ar.ar005 = 380 or ar.ar005 = 381)) then 'ERRO'
		else ''
	      end as VAL_EAR005

	    , case
		when (ar.ar006 != null and ar.ar006c = null and (ar.ar006 = 380 or ar.ar006 = 381)) then 'ERRO'
		else ''
	      end as VAL_EAR006

	    , case
		when (ar.ar007 != null and ar.ar007c = null and (ar.ar007 = 380 or ar.ar007 = 381)) then 'ERRO'
		else ''
	      end as VAL_EAR007

	    , case
		when (ar.ar008 != null and ar.ar008c = null and (ar.ar008 = 380 or ar.ar008 = 381)) then 'ERRO'
		else ''
	      end as VAL_EAR008

	    , case
		when (ar.ar009 != null and ar.ar009c = null and (ar.ar009 = 380 or ar.ar009 = 381)) then 'ERRO'
		else ''
	      end as VAL_EAR009

	    , case
		when (ar.ar010 != null and ar.ar010c = null and (ar.ar010 = 380 or ar.ar010 = 381)) then 'ERRO'
		else ''
	      end as VAL_EAR010

	    , case
		when (cb.cb003 != null and ge.ge007 != null and (cb.cb003 > ge.ge007)) then 'ERRO'
		else ''
	      end as VAL_ECB001

	    , case
	        when (array[2] <@ array(select ca.sgl_valor from drenagem.vis_campo_multi_opcoes ca where cod_sequencia_tabela_pai = cb.cb002)::int[] = true) and (cb.cb003 = null or cb.cb003 = 0) then 'ERRO'
		else ''
	      end as VAL_ECB002

	    , case
	        when (cb.cb004 != null and cb.cb003 != null and cb.cb004 = 0 and cb.cb003 != 0) then 'ERRO'
		else ''
	      end as VAL_ECB003

	    , case
	        when (fn.ad003 != null and ge.ge005 != null and (fn.ad003 > ge.ge005)) then 'ERRO'
		else ''
	      end as VAL_EFN001

	    , case
	        when (fn.fn003 != null and fn.fn003 = 0.00) then 'ERRO'
		else ''
	      end as VAL_EFN002

	    , case
	        when (array[2] <@ array(select ca.sgl_valor from drenagem.vis_campo_multi_opcoes ca where cod_sequencia_tabela_pai = cb.cb002)::int[] = true) and (fn.fn005 = null or fn.fn005 = 0) then 'ERRO'
		else ''
	      end as VAL_EFN003	      

	    , case
	        when (array[1] <@ array(select ca.sgl_valor from drenagem.vis_campo_multi_opcoes ca where cod_sequencia_tabela_pai = fn.fn004)::int[] = true) and (fn.fn005 = null or fn.fn005 = 0) then 'ERRO'
		else ''
	      end as VAL_EFN004

	    , case
	        when (fn.fn012 != null and fn.fn012 = 0.00) then 'ERRO'
		else ''
	      end as VAL_EFN005

	    , case
	        when (fn.fn023 != null and fn.fn003 != null and (fn.fn023 > fn.fn003)) then 'ERRO'
		else ''
	      end as VAL_EFN006
	      
	    , case
	        when (fn.fn009 != null and fn.fn003 != null and (fn.fn009 > fn.fn003)) then 'ERRO'
		else ''
	      end as VAL_EFN007

	    , case
	        when (fn.fn016 != null and fn.fn012 != null and (fn.fn016 > fn.fn012)) then 'ERRO'
		else ''
	      end as VAL_EFN008

	    , case
	        when (ge.ge002 != null and ge.ge001 != null and (ge.ge002 > ge.ge001)) then 'ERRO'
		else ''
	      end as VAL_EGE001

	    , case
	        when (ge.ge008 != null and ge.ge007 != null and (ge.ge008 > ge.ge007)) then 'ERRO'
		else ''
	      end as VAL_EGE002
	      	      	      	      	      	      	          	      	    	      	      	      	      	          	      	    
	    , case
	        when (ie.ie017 != null and ie.ie017 = 0) then 'ERRO'
		else ''
	      end as VAL_EIE001

	    , case
	        when (ie.ie018 != null and ie.ie017 != null and (ie.ie018 > ie.ie017)) then 'ERRO'
		else ''
	      end as VAL_EIE002

	    , case
	        when (ie.ie019 != null and ie.ie017 != null and (ie.ie019 > ie.ie017)) then 'ERRO'
		else ''
	      end as VAL_EIE003

	    , case
	        when (ie.ie020 != null and ie.ie019 != null and (ie.ie020 > ie.ie019)) then 'ERRO'
		else ''
	      end as VAL_EIE004

	    , case
	        when (ie.ie024 != null and ie.ie017 != null and (ie.ie024 > ie.ie017)) then 'ERRO'
		else ''
	      end as VAL_EIE005

	    , case
	        when (ie.ie025 != null and ie.ie024 != null and (ie.ie025 > ie.ie024)) then 'ERRO'
		else ''
	      end as VAL_EIE006

	    , case
	        when (ie.ie028 != null and ie.ie017 != null and (ie.ie028 > ie.ie017)) then 'ERRO'
		else ''
	      end as VAL_EIE007	      

	    , case
	        when (ie.ie033 != null and ie.ie032 != null and (ie.ie033 > ie.ie032)) then 'ERRO'
		else ''
	      end as VAL_EIE008	      

	    , case
	        when (ie.ie034 != null and ie.ie032 != null and (ie.ie034 > ie.ie032)) then 'ERRO'
		else ''
	      end as VAL_EIE009	      

	    , case
	        when (ie.ie035 != null and ie.ie032 != null and (ie.ie035 > ie.ie032)) then 'ERRO'
		else ''
	      end as VAL_EIE010	      	      

	    , case
	        when (ie.ie036 != null and ie.ie032 != null and (ie.ie036 > ie.ie032)) then 'ERRO'
		else ''
	      end as VAL_EIE011	      	      

	    , case
	        when (ie.ie037 != null and ie.ie032 != null and (ie.ie037 > ie.ie032)) then 'ERRO'
		else ''
	      end as VAL_EIE012	      	      

	    , case
	        when (ie.ie040 != null and ie.ie032 != null and (ie.ie040 > ie.ie032)) then 'ERRO'
		else ''
	      end as VAL_EIE013	      	      

	    , case
	        when (ie.ie044 != null and ie.ie032 != null and (ie.ie044 > ie.ie032)) then 'ERRO'
		else ''
	      end as VAL_EIE014	      

	    , case
	        when (ie.ie043 = 8 and (array_to_string(array(select ie061 from drenagem.tab_infraestruturas_parques where cod_infraestrutura_fk = ie.cod_infraestrutura and dte_exclusao is null), ',') = '')) then 'ERRO'
		else ''
	      end as VAL_EIE016

	    , case
	        when (ri.ri013 != null and ge.ge008 != null and (ri.ri013 > ge.ge008)) then 'ERRO'
		else ''
	      end as VAL_ERI001

	    , case
	        when (ri.ri068 != null and ge.ge005 != null and (ri.ri068 > ge.ge005)) then 'ERRO'
		else ''
	      end as VAL_ERI007
	      	      	      	      	      	      	      	      	      	      	      	      	      	      	          	      	    	      	      	      	      	          	      	    
	from tab_prestadores pr
	inner join rlc_modulos_prestadores mp on mp.cod_prestador_fk = pr.cod_prestador
	inner join drenagem.tab_participacoes pa on pa.cod_modulo_prestador_fk = mp.cod_modulo_prestador
	inner join tab_municipios mu on mu.cod_municipio = pr.cod_municipio_fk
	inner join tab_estados es on es.sgl_estado = mu.sgl_estado_fk
        left join drenagem.tab_avaliacoes_reacoes ar on ar.participacao_fk = pa.cod_participacao
	left join drenagem.tab_gerais ge on ge.participacao_fk = pa.cod_participacao
	left join drenagem.tab_cobrancas cb on cb.participacao_fk = pa.cod_participacao
	left join drenagem.tab_financeiros fn on fn.participacao_fk = pa.cod_participacao
	left join drenagem.tab_infraestruturas ie on ie.participacao_fk = pa.cod_participacao
	left join drenagem.tab_riscos ri on ri.participacao_fk = pa.cod_participacao	
	where 1 = 1
	and pa.ano_ref = '2015'
	and pa.cod_situacao_preenchimento_fk in (58, 62)
	order by mu.cod_ibge
	    , pr.dg002
) as tabela
where VAL_EAR001 = 'ERRO'
or VAL_EAR002 = 'ERRO'
or VAL_EAR003 = 'ERRO'
or VAL_EAR004 = 'ERRO'
or VAL_EAR005 = 'ERRO'
or VAL_EAR006 = 'ERRO'
or VAL_EAR007 = 'ERRO'
or VAL_EAR008 = 'ERRO'
or VAL_EAR009 = 'ERRO'
or VAL_EAR010 = 'ERRO'
or VAL_ECB001 = 'ERRO'
or VAL_ECB002 = 'ERRO'
or VAL_ECB003 = 'ERRO'
or VAL_EFN001 = 'ERRO'
or VAL_EFN002 = 'ERRO'
or VAL_EFN003 = 'ERRO'
or VAL_EFN004 = 'ERRO'
or VAL_EFN005 = 'ERRO'
or VAL_EFN006 = 'ERRO'
or VAL_EFN007 = 'ERRO'
or VAL_EFN008 = 'ERRO'
or VAL_EGE001 = 'ERRO'
or VAL_EGE002 = 'ERRO'
or VAL_EIE001 = 'ERRO'
or VAL_EIE002 = 'ERRO'
or VAL_EIE003 = 'ERRO'
or VAL_EIE004 = 'ERRO'
or VAL_EIE005 = 'ERRO'
or VAL_EIE006 = 'ERRO'
or VAL_EIE007 = 'ERRO'
or VAL_EIE008 = 'ERRO'
or VAL_EIE009 = 'ERRO'
or VAL_EIE010 = 'ERRO'
or VAL_EIE011 = 'ERRO'
or VAL_EIE012 = 'ERRO'
or VAL_EIE013 = 'ERRO'
or VAL_EIE014 = 'ERRO'
or VAL_EIE016 = 'ERRO'
or VAL_ERI001 = 'ERRO'
or VAL_ERI007 = 'ERRO'