DELETE FROM acesso.rlc_menus_perfis
 WHERE cod_perfil_fk not in (1);


DELETE FROM acesso.rlc_perfis_funcionalidades_acoes
 WHERE cod_perfil_fk not in (1);

 
 
DELETE FROM acesso.rlc_usuarios_perfis
 WHERE cod_perfil_fk not in (1)

DELETE FROM acesso.tab_perfis
 WHERE cod_perfil not in (1)



DELETE FROM acesso.rlc_usuarios_perfis
 WHERE cod_usuario_fk not in  (1,5)

 
DELETE FROM acesso.tab_usuarios
 WHERE cod_usuario not in (1,5)

 DELETE FROM public.tab_parametros
 WHERE modulo_fk=2

 DELETE FROM acesso.tab_modulos
 WHERE cod_modulo not in (1)

DELETE FROM acesso.tab_menus
 WHERE cod_menu not in ( 
 select cod_menu_fk FROM acesso.rlc_menus_perfis
 WHERE cod_perfil_fk in (1)
 );
