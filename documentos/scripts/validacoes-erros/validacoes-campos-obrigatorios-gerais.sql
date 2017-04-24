/*
select ',case when ' || lower(sgl_cod_info) || ' is null then "ERRO" else "" end as ' || lower(sgl_cod_info) as condicao
--select 'or ' || lower(sgl_cod_info) || ' = "ERRO" ' as condicao
from dicionario.tab_glossarios
where fk_attr_servico = 2
    and num_ano_ref = 2015
    and bln_obrigatorio = true
    and bln_info_ativa = true
    and dte_exclusao is null
order by sgl_cod_info
*/

select *
from (
	select mu.cod_ibge
		, mu.txt_nome as municipio
		, mu.sgl_estado_fk || ' - ' || es.txt_nome as uf             

		,case when ad001 is null then 'ERRO' else '' end as ad001
		,case when ad002 is null then 'ERRO' else '' end as ad002
		,case when ar001 is null then 'ERRO' else '' end as ar001
		,case when ar002 is null then 'ERRO' else '' end as ar002
		,case when ar003 is null then 'ERRO' else '' end as ar003
		,case when ar004 is null then 'ERRO' else '' end as ar004
		,case when ar005 is null then 'ERRO' else '' end as ar005
		,case when ar006 is null then 'ERRO' else '' end as ar006
		,case when ar007 is null then 'ERRO' else '' end as ar007
		,case when ar008 is null then 'ERRO' else '' end as ar008
		,case when ar009 is null then 'ERRO' else '' end as ar009
		,case when ar010 is null then 'ERRO' else '' end as ar010
		,case when cb001 is null then 'ERRO' else '' end as cb001
		,case when cb002 is null then 'ERRO' else '' end as cb002
		,case
	            when (array[2] <@ array(select ca.sgl_valor from drenagem.vis_campo_multi_opcoes ca where cod_sequencia_tabela_pai = cb.cb002)::int[] = true) and (cb.cb003 is null) then 'ERRO'
		    else ''
	          end as cb003	      				
		,case
	            when (array[2] <@ array(select ca.sgl_valor from drenagem.vis_campo_multi_opcoes ca where cod_sequencia_tabela_pai = cb.cb002)::int[] = true) and (cb.cb004 is null) then 'ERRO'
		    else ''
	          end as cb004
		,case when cp001 is null then 'ERRO' else '' end as cp001
		,case when cp002 is null then 'ERRO' else '' end as cp002
		,case when cp003 is null then 'ERRO' else '' end as cp003
		,case when cp004 is null then 'ERRO' else '' end as cp004
		,case when cp008 is null then 'ERRO' else '' end as cp008
		,case when cp015 is null then 'ERRO' else '' end as cp015
		,case when cp017 is null then 'ERRO' else '' end as cp017
		,case when cp018 is null then 'ERRO' else '' end as cp018
		,case when cp019 is null then 'ERRO' else '' end as cp019
		,case when cp021 is null then 'ERRO' else '' end as cp021
		,case when cp028 is null then 'ERRO' else '' end as cp028
		,case when cp032 is null then 'ERRO' else '' end as cp032
		,case when cp033 is null then 'ERRO' else '' end as cp033
		,case when cp034 is null then 'ERRO' else '' end as cp034
		,case when cp036 is null then 'ERRO' else '' end as cp036
		,case when cp042 is null then 'ERRO' else '' end as cp042
		,case when cp061 is null then 'ERRO' else '' end as cp061
		,case when cp062 is null then 'ERRO' else '' end as cp062
		,case when cp064 is null then 'ERRO' else '' end as cp064
		,case when dg001 is null then 'ERRO' else '' end as dg001
		,case when dg002 is null then 'ERRO' else '' end as dg002
		,case when dg004 is null then 'ERRO' else '' end as dg004
		,case when dg005 is null then 'ERRO' else '' end as dg005
		,case when dg007 is null then 'ERRO' else '' end as dg007
		,case when dg010 is null then 'ERRO' else '' end as dg010
		,case when dg018 is null then 'ERRO' else '' end as dg018
		,case when dg020 is null then 'ERRO' else '' end as dg020
		,case when dg021 is null then 'ERRO' else '' end as dg021
		,case when dg024 is null then 'ERRO' else '' end as dg024
		,case when dg030 is null then 'ERRO' else '' end as dg030
	        , case
	            when (array[2] <@ array(select ca.sgl_valor from drenagem.vis_campo_multi_opcoes ca where cod_sequencia_tabela_pai = cb.cb002)::int[] = true) and (fn.fn005 is null) then 'ERRO'
		    else ''
	          end as fn005	      		
		,case when fn008 is null then 'ERRO' else '' end as fn008
		,case when fn013 is null then 'ERRO' else '' end as fn013
		,case when fn015 is null then 'ERRO' else '' end as fn015
		,case when ge002 is null then 'ERRO' else '' end as ge002
		,case when ge007 is null then 'ERRO' else '' end as ge007
		,case when ge008 is null then 'ERRO' else '' end as ge008
		,case when ie001 is null then 'ERRO' else '' end as ie001
		,case when ie012 is null then 'ERRO' else '' end as ie012
		,case when ie013 is null then 'ERRO' else '' end as ie013
		,case when ie016 is null then 'ERRO' else '' end as ie016
		,case when ie017 is null then 'ERRO' else '' end as ie017
		,case when ie021 is null then 'ERRO' else '' end as ie021
		,case when ie022 is null then 'ERRO' else '' end as ie022
		,case when ie023 is null then 'ERRO' else '' end as ie023
		,case when ie024 is null then 'ERRO' else '' end as ie024
		,case when ie031 is null then 'ERRO' else '' end as ie031
		,case when ie031 = 8 and ie032 is null then 'ERRO' else '' end as ie032
		,case when ie031 = 8 and ie033 is null then 'ERRO' else '' end as ie033
		,case when ie031 = 8 and ie034 is null then 'ERRO' else '' end as ie034
		,case when ie031 = 8 and ie035 is null then 'ERRO' else '' end as ie035
		,case when ie031 = 8 and ie036 is null then 'ERRO' else '' end as ie036
		,case when ie043 is null then 'ERRO' else '' end as ie043
		,case when ie043 = 8 and ie044 is null then 'ERRO' else '' end as ie044		
	        , case
	            when (ie.ie043 = 8 and (array_to_string(array(select ie061 from drenagem.tab_infraestruturas_parques where cod_infraestrutura_fk = ie.cod_infraestrutura and dte_exclusao is null), ',') = '')) then 'ERRO'
		    else ''
	          end as ie061l		
		,case when op001 is null then 'ERRO' else '' end as op001
		,case when pa002 is null then 'ERRO' else '' end as pa002
		,case when ri001 is null then 'ERRO' else '' end as ri001
		,case when ri005 is null then 'ERRO' else '' end as ri005
		,case when ri009 is null then 'ERRO' else '' end as ri009
		,case when ri009 = 8 and ri010 is null then 'ERRO' else '' end as ri010
		,case when ri010 = 123 and ri011 is null then 'ERRO' else '' end as ri011
		,case when ri009 = 8 and ri012 is null then 'ERRO' else '' end as ri012
		,case when ri042 is null then 'ERRO' else '' end as ri042
		,case when ri064 is null then 'ERRO' else '' end as ri064
		,case when ri065 is null then 'ERRO' else '' end as ri065
		,case when ri066 is null then 'ERRO' else '' end as ri066
		,case when ri067 is null then 'ERRO' else '' end as ri067
	      	      	      	      	      	      	      	      	      	      	      	      	          	      	    	      	      	      	      	          	      	    
	from tab_prestadores pr
	inner join rlc_modulos_prestadores mp on mp.cod_prestador_fk = pr.cod_prestador
	inner join drenagem.tab_participacoes pa on pa.cod_modulo_prestador_fk = mp.cod_modulo_prestador
	inner join tab_municipios mu on mu.cod_municipio = pr.cod_municipio_fk
	inner join tab_estados es on es.sgl_estado = mu.sgl_estado_fk
        left join drenagem.tab_avaliacoes_reacoes ar on ar.participacao_fk = pa.cod_participacao
	left join drenagem.tab_gerais ge on ge.participacao_fk = pa.cod_participacao
	left join drenagem.tab_cobrancas cb on cb.participacao_fk = pa.cod_participacao
	left join drenagem.tab_financeiros fn on fn.participacao_fk = pa.cod_participacao
	left join drenagem.tab_operacionais op on op.participacao_fk = pa.cod_participacao
	left join drenagem.tab_infraestruturas ie on ie.participacao_fk = pa.cod_participacao
	left join drenagem.tab_riscos ri on ri.participacao_fk = pa.cod_participacao	
	where 1 = 1
	and pa.ano_ref = '2015'	
	and pa.cod_situacao_preenchimento_fk in (58, 62)
	--and mu.txt_nome = 'Ariquemes' --Campo Limpo de Goiás
	order by mu.cod_ibge
	    , pr.dg002
) as tabela
where ad001 = 'ERRO' 
or ad002 = 'ERRO' 
or ar001 = 'ERRO' 
or ar002 = 'ERRO' 
or ar003 = 'ERRO' 
or ar004 = 'ERRO' 
or ar005 = 'ERRO' 
or ar006 = 'ERRO' 
or ar007 = 'ERRO' 
or ar008 = 'ERRO' 
or ar009 = 'ERRO' 
or ar010 = 'ERRO' 
or cb001 = 'ERRO' 
or cb002 = 'ERRO' 
or cb003 = 'ERRO' 
or cb004 = 'ERRO' 
or cp001 = 'ERRO' 
or cp002 = 'ERRO' 
or cp003 = 'ERRO' 
or cp004 = 'ERRO' 
or cp008 = 'ERRO' 
or cp015 = 'ERRO' 
or cp017 = 'ERRO' 
or cp018 = 'ERRO' 
or cp019 = 'ERRO' 
or cp021 = 'ERRO' 
or cp028 = 'ERRO' 
or cp032 = 'ERRO' 
or cp033 = 'ERRO' 
or cp034 = 'ERRO' 
or cp036 = 'ERRO' 
or cp042 = 'ERRO' 
or cp061 = 'ERRO' 
or cp062 = 'ERRO' 
or cp064 = 'ERRO' 
or dg001 = 'ERRO' 
or dg002 = 'ERRO' 
or dg004 = 'ERRO' 
or dg005 = 'ERRO' 
or dg007 = 'ERRO' 
or dg010 = 'ERRO' 
or dg018 = 'ERRO' 
or dg020 = 'ERRO' 
or dg021 = 'ERRO' 
or dg024 = 'ERRO' 
or dg030 = 'ERRO' 
or fn005 = 'ERRO' 
or fn008 = 'ERRO' 
or fn013 = 'ERRO' 
or fn015 = 'ERRO' 
or ge002 = 'ERRO' 
or ge007 = 'ERRO' 
or ge008 = 'ERRO' 
or ie001 = 'ERRO' 
or ie012 = 'ERRO' 
or ie013 = 'ERRO' 
or ie016 = 'ERRO' 
or ie017 = 'ERRO' 
or ie021 = 'ERRO' 
or ie022 = 'ERRO' 
or ie023 = 'ERRO' 
or ie024 = 'ERRO' 
or ie031 = 'ERRO' 
or ie032 = 'ERRO' 
or ie033 = 'ERRO' 
or ie034 = 'ERRO' 
or ie035 = 'ERRO' 
or ie036 = 'ERRO' 
or ie043 = 'ERRO' 
or ie044 = 'ERRO' 
or ie061l = 'ERRO' 
or op001 = 'ERRO' 
or pa002 = 'ERRO' 
or ri001 = 'ERRO' 
or ri005 = 'ERRO' 
or ri009 = 'ERRO' 
or ri010 = 'ERRO' 
or ri011 = 'ERRO' 
or ri012 = 'ERRO' 
or ri042 = 'ERRO' 
or ri064 = 'ERRO' 
or ri065 = 'ERRO' 
or ri066 = 'ERRO' 
or ri067 = 'ERRO' 