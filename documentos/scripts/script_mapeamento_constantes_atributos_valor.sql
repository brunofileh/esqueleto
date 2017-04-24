SELECT 'const ' ||  UPPER(REPLACE(sgl_chave, '-', '_')) || ' = ' || cod_atributos || '; // ' || dsc_descricao AS descricao_completa
, cod_atributos
, dsc_descricao
FROM public.tab_atributos
ORDER BY cod_atributos