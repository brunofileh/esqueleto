select distinct ae.txt_codigo
    , ae.txt_formula
from dicionario.tab_avisos_erros ae
where ae.fk_modulo = 2
    and fk_attr_tipo_aviso_erro = 221
    and ae.num_ano_ref = 2015
    and ae.bln_ativo = true
    and ae.dte_exclusao is null
order by ae.txt_codigo