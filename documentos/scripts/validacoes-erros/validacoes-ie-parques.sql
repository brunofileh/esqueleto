select *
from (
	select mu.cod_ibge
	    , mu.txt_nome as municipio
	    , mu.sgl_estado_fk || ' - ' || es.txt_nome as uf             

	    , case
	        when (ip.ie064 != null and ge.ge002 != null and (ip.ie064 > (ge.ge002 * 1000000))) then 'ERRO'
		else ''
	      end as VAL_EIE015	      	      
	      	      	      	      	      	      	      	      	      	      	      	      	          	      	    	      	      	      	      	          	      	    
	from tab_prestadores pr
	inner join rlc_modulos_prestadores mp on mp.cod_prestador_fk = pr.cod_prestador
	inner join drenagem.tab_participacoes pa on pa.cod_modulo_prestador_fk = mp.cod_modulo_prestador
	inner join tab_municipios mu on mu.cod_municipio = pr.cod_municipio_fk
	inner join tab_estados es on es.sgl_estado = mu.sgl_estado_fk
	left join drenagem.tab_gerais ge on ge.participacao_fk = pa.cod_participacao
	left join drenagem.tab_infraestruturas ie on ie.participacao_fk = pa.cod_participacao
	inner join drenagem.tab_infraestruturas_parques ip on ip.cod_infraestrutura_fk = ie.cod_infraestrutura	
	where 1 = 1
	and pa.ano_ref = '2015'	
	and pa.cod_situacao_preenchimento_fk in (58, 62)
	order by mu.cod_ibge
	    , pr.dg002
) as tabela
where VAL_EIE015 = 'ERRO'