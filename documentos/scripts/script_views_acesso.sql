
CREATE OR REPLACE VIEW acesso.vis_usuarios_perfis AS 
 SELECT u.txt_nome AS nome_usuario,
    u.txt_email,
    u.txt_senha,
    u.num_fone,
    u.qtd_acesso,
    u.txt_trocar_senha,
    u.txt_ativo,
    u.txt_tipo_login,
    u.num_ip,
    u.dte_sessao,
    u.txt_login,
    u.num_cpf,
    p.txt_nome AS nome_perfil,
    p.dsc_perfil,
    m.txt_nome AS nome_modulo,
    m.dsc_modulo,
    u.cod_usuario AS cod_usuario_fk,
    p.cod_perfil AS cod_perfil_fk,
    m.cod_modulo AS cod_modulo_fk,
    m.id AS modulo_id,
    m.txt_url AS modulo_url,
    m.txt_icone AS modulo_icone,
    p.txt_perfil_prestador
   FROM acesso.tab_usuarios u
     LEFT JOIN acesso.rlc_usuarios_perfis up ON u.cod_usuario = up.cod_usuario_fk
     LEFT JOIN acesso.tab_perfis p ON up.cod_perfil_fk = p.cod_perfil
     LEFT JOIN acesso.tab_modulos m ON p.cod_modulo_fk = m.cod_modulo
  WHERE u.dte_exclusao IS NULL AND up.dte_exclusao IS NULL AND p.dte_exclusao IS NULL AND m.dte_exclusao IS NULL;

ALTER TABLE acesso.vis_usuarios_perfis
  OWNER TO snsa;


---------------------------------------------------------------
CREATE OR REPLACE VIEW acesso.vis_menus_perfis AS 
 SELECT m.txt_nome AS nome_menu,
    m.dsc_menu,
    m.txt_url,
    m.txt_imagem,
    m.num_ordem,
    m.num_nivel,
    p.txt_nome AS nome_perfil,
    p.dsc_perfil,
    mm.txt_nome AS nome_menu_pai,
    mm.dsc_menu AS dsc_menu_pai,
    mp.cod_perfil_fk,
    m.cod_perfil_funcionalidade_acao_fk,
    m.cod_menu AS cod_menu_fk,
    m.cod_menu_fk AS cod_menu_pai_fk,
    p.cod_modulo_fk,
    up.cod_usuario_fk
   FROM acesso.tab_menus m
     LEFT JOIN acesso.tab_menus mm ON m.cod_menu_fk = mm.cod_menu
     JOIN acesso.rlc_menus_perfis mp ON m.cod_menu = mp.cod_menu_fk
     JOIN acesso.tab_perfis p ON mp.cod_perfil_fk = p.cod_perfil
     LEFT JOIN acesso.rlc_usuarios_perfis up ON p.cod_perfil = up.cod_perfil_fk
     LEFT JOIN acesso.rlc_perfis_funcionalidades_acoes pfa ON m.cod_perfil_funcionalidade_acao_fk = pfa.cod_perfil_funcionalidade_acao
  WHERE NOT (EXISTS ( SELECT ru.cod_restricao_usuario
           FROM acesso.tab_restricoes_usuarios ru
          WHERE ru.cod_perfil_funcionalidade_acao_fk = pfa.cod_perfil_funcionalidade_acao AND ru.cod_usuario_fk = up.cod_usuario_fk));


--------------------------------------------------------------

CREATE OR REPLACE VIEW acesso.vis_perfis_funcionalidades_acoes AS 
 SELECT pfa.cod_perfil_funcionalidade_acao,
    p.cod_perfil,
    f.dsc_funcionalidade,
    a.dsc_acao,
    (f.dsc_funcionalidade::text || ' - '::text) || a.dsc_acao::text AS funcionalidade_acao
   FROM acesso.rlc_perfis_funcionalidades_acoes pfa
     JOIN acesso.tab_perfis p ON pfa.cod_perfil_fk = p.cod_perfil
     JOIN acesso.tab_acoes a ON pfa.cod_acao_fk = a.cod_acao
     JOIN acesso.tab_funcionalidades f ON pfa.cod_funcionalidade_fk = f.cod_funcionalidade
  WHERE pfa.dte_exclusao IS NULL;

ALTER TABLE acesso.vis_perfis_funcionalidades_acoes
  OWNER TO snsa;

--------------------------------------------------------------

CREATE OR REPLACE VIEW acesso.vis_perfis_funcionalidades_acoes_modulos AS 
 SELECT p.txt_nome AS nome_perfil,
    p.dsc_perfil,
    f.txt_nome AS nome_funcionalidade,
    f.dsc_funcionalidade,
    a.txt_nome AS nome_acao,
    m.txt_url,
    m.txt_icone,
    m.txt_nome AS nome_modulo,
    m.id AS modulo_id,
    m.dsc_modulo,
    pfa.cod_perfil_fk,
    pfa.cod_funcionalidade_fk,
    pfa.cod_acao_fk,
    p.cod_modulo_fk,
    up.cod_usuario_fk,
    pfa.cod_perfil_funcionalidade_acao AS cod_perfil_funcionalidade_acao_fk
   FROM acesso.rlc_perfis_funcionalidades_acoes pfa
     JOIN acesso.tab_perfis p ON pfa.cod_perfil_fk = p.cod_perfil
     JOIN acesso.tab_funcionalidades f ON pfa.cod_funcionalidade_fk = f.cod_funcionalidade
     JOIN acesso.tab_acoes a ON pfa.cod_acao_fk = a.cod_acao
     JOIN acesso.tab_modulos m ON p.cod_modulo_fk = m.cod_modulo
     JOIN acesso.rlc_usuarios_perfis up ON p.cod_perfil = up.cod_perfil_fk
  WHERE NOT (EXISTS ( SELECT ru.cod_restricao_usuario
           FROM acesso.tab_restricoes_usuarios ru
          WHERE ru.cod_perfil_funcionalidade_acao_fk = pfa.cod_perfil_funcionalidade_acao AND ru.cod_usuario_fk = up.cod_usuario_fk AND ru.dte_exclusao IS NULL));

ALTER TABLE acesso.vis_perfis_funcionalidades_acoes_modulos
  OWNER TO snsa;

--------------------------------------------------------------

CREATE OR REPLACE VIEW acesso.vis_modulos_perfis_menus_funcionalidades AS 
	select distinct mo.cod_modulo,mo.txt_nome as nome_modulo,mo.dsc_modulo as dsc_modulo
		,p.cod_perfil,p.txt_nome as nome_perfil,p.dsc_perfil,p.txt_perfil_prestador
		,m2.cod_menu as cod_menu_pai,m2.txt_nome as nome_menu_pai,m2.dsc_menu as dsc_menu_pai
		,m.cod_menu,m.txt_nome as nome_menu,m.dsc_menu,m.num_ordem
		,f.cod_funcionalidade,f.txt_nome as nome_funcionalidade,f.dsc_funcionalidade
	from acesso.tab_funcionalidades f
	inner join acesso.rlc_perfis_funcionalidades_acoes pfa on pfa.cod_funcionalidade_fk = f.cod_funcionalidade
	inner join acesso.tab_menus m on m.cod_perfil_funcionalidade_acao_fk = f.cod_funcionalidade
	left join acesso.tab_menus m2 on m2.cod_menu = m.cod_menu_fk
	inner join acesso.tab_perfis p on p.cod_perfil = pfa.cod_perfil_fk
	inner join acesso.tab_modulos mo on mo.cod_modulo = p.cod_modulo_fk
	where true
	and f.dte_exclusao is null
	and pfa.dte_exclusao is null
	and m.dte_exclusao is null
	and m2.dte_exclusao is null
	and p.dte_exclusao is null
	and mo.dte_exclusao is null;

ALTER TABLE acesso.vis_modulos_perfis_menus_funcionalidades
  OWNER TO snsa;