begin;

---------------------------Usuarios Teste

INSERT INTO acesso.tab_usuarios(
            txt_login_inclusao, 
            txt_nome, txt_email, txt_senha, num_fone, qtd_acesso, txt_trocar_senha, txt_ativo, txt_tipo_login)
    VALUES ('1', 'Administrador', 'administrador@cidades.gov.br', '$2y$13$bh0cbcwWRZY8qtR/SXfndu4.wcKmIPXUEsaPxzaoZagI56008OkTG',  '(61) 1234-1234',
    		   '0', '1',  '1', '1');

INSERT INTO acesso.tab_usuarios(
           txt_login_inclusao, 
            txt_nome, txt_email, txt_senha, num_fone, qtd_acesso, txt_trocar_senha, txt_ativo, txt_tipo_login)
    VALUES ('1', 'Coordenador', 'coordenador@cidades.gov.br', '$2y$13$bh0cbcwWRZY8qtR/SXfndu4.wcKmIPXUEsaPxzaoZagI56008OkTG',  '(61) 1234-1234',
    		  '0', '1',  '1', '1');

INSERT INTO acesso.tab_usuarios(
            txt_login_inclusao, 
            txt_nome, txt_email, txt_senha, num_fone, qtd_acesso, txt_trocar_senha, txt_ativo, txt_tipo_login)
    VALUES ('1', 'Analista', 'analista@cidades.gov.br', '$2y$13$bh0cbcwWRZY8qtR/SXfndu4.wcKmIPXUEsaPxzaoZagI56008OkTG',  '(61) 1234-1234',
    		  '0', '1',  '1', '1');

INSERT INTO acesso.tab_usuarios(
            txt_login_inclusao, 
            txt_nome, txt_email, txt_senha, num_fone, qtd_acesso, txt_trocar_senha, txt_ativo, txt_tipo_login)
    VALUES ('1', 'Consulta', 'consulta@cidades.gov.br', '$2y$13$bh0cbcwWRZY8qtR/SXfndu4.wcKmIPXUEsaPxzaoZagI56008OkTG',  '(61) 1234-1234',
    		  '0', '1',  '1', '1');


----------------------------Modulos Teste

INSERT INTO acesso.tab_modulos(
            txt_nome, dsc_modulo, 
            txt_url, txt_icone, txt_login_inclusao, id)
    VALUES
('Controle de Acesso', 'Módulo gerenciador do controle de acesso dos usuários', '/admin', '/img/icones/modulos/admin.png','anderson.priedols@cidades.gov.br', 'acesso'),
('Água e Esgotos', 'Módulo de água e esgotos', '/agua-esgotos', '/img/icones/modulos/agua-esgotos.png','anderson.priedols@cidades.gov.br', 'agua-esgoto');



----------------------------Perfis Teste

INSERT INTO acesso.tab_perfis(
            cod_modulo_fk, txt_login_inclusao, txt_nome, dsc_perfil)
    VALUES
    (1, '1', 'Administrador', 'Administrador Controle de Acesso'),
 	(1, '1', 'Coordenador', 'Coordenador Controle de Acesso'),
    (1, '1', 'Analista', 'Analista Controle de Acesso'),
    (1, '1', 'Consulta', 'Consulta Controle de Acesso'),
	(2, '1', 'Administrador', 'Água e Esgotos');



---------------------------Vinculo do perfil com usuario

INSERT INTO acesso.rlc_usuarios_perfis(
            cod_usuario_fk, cod_perfil_fk, txt_login_inclusao)
    VALUES 	 ('1', '1', '1'),
    		 ('2', '2', '1'),
    		 ('3', '3', '1'),
    		 ('4', '4', '1'),
    		 ('1', '5', '1');



--------------------------Menus

INSERT INTO acesso.tab_menus(
            txt_login_inclusao, 
             txt_nome, dsc_menu, txt_url, txt_imagem, num_ordem, 
            num_nivel)
    VALUES 
	('1', 'modulos', 'Módulo', '/admin/modulos', 'imagem', '1' , '1'),
	('1', 'perfis', 'Perfis', '/admin/perfis', 'imagem', '2' , '1'),
	('1', 'funcionalidades', 'Funcionalidades', '/admin/funcionalidades', 'imagem', '3' , '1'),
	('1', 'acoes', 'Ações', '/admin/acoes', 'imagem', '4' , '1'),
	('1', 'usuario', 'Usuários', '/admin/usuarios', 'imagem', '5' , '1'),
	('1', 'menus', 'Menus', '/admin/menus', 'imagem', '6' , '1'),
	('1', 'restricoesUsuarios', 'Restrições', '/admin/restricoes-usuarios', 'imagem', '6' , '1');



------------------------Vinculos do menu com perfil
--administrador com todos os menus
--coordenador com mudulos, perfils, usuarios, menus, restrições
--analista com funcionalidades e acoes
--consulta com todos os menus
INSERT INTO acesso.rlc_menus_perfis (cod_perfil_fk, cod_menu_fk, txt_login_inclusao)
    VALUES 
		 ('1', '1','1'),
		 ('1', '2','1'),
		 ('1', '3','1'), 
		 ('1', '4','1'),
		 ('1', '5','1'),
		 ('1', '6','1'),
		 ('1', '7','1'),
         ('1', '8','1'),

		 ('2', '1','1'),
		 ('2', '2','1'),
		 ('2', '5','1'), 
		 ('2', '6','1'),
		 ('2', '7','1'),

		 ('3', '3','1'),
		 ('3', '4','1'),

		 ('4', '1','1'),
		 ('4', '2','1'),
		 ('4', '3','1'), 
		 ('4', '4','1'),
		 ('4', '5','1'),
		 ('4', '6','1'),
		 ('4', '7','1');


----------------------------------Funcionalidades

INSERT INTO acesso.tab_funcionalidades(
             txt_login_inclusao, txt_nome, dsc_funcionalidade)
    VALUES 
 	('1', 'modulos', 'Módulos'),
	('1', 'perfis', 'Perfis'),
	('1', 'funcionalidades', 'Funcionalidades'),
	('1', 'acoes', 'Ações'),
	('1', 'usuarios', 'Usuários'),
	('1', 'menus', 'Menus'),
	('1', 'restricoesUsuarios', 'Restrições')
    ;

    
    
-----------------------------------Ações

INSERT INTO acesso.tab_acoes(
             txt_login_inclusao, txt_nome, dsc_acao)
    VALUES 
			('1', 'index', 'Página inicia'),
			('1', 'view', 'Visualizar'),			
			('1', 'create', 'Criar'),
    		('1', 'update', 'Alterar'),
    		('1', 'delete', 'Excluir');



------------------------Vinculos perfil com acao e funcionalidades
--administrador com todas as funcionalidades e acoes
--coordenador com todas as funcionalidades dos mudulos (mudulos, perfils, usuarios, menus, restrições)
--analista com todas as funcionalidades dos mudulos (funcionalidades e acoes)
--consulta podendo soh visualizar a index de todos os módulos

INSERT INTO acesso.rlc_perfis_funcionalidades_acoes
(cod_perfil_fk, cod_funcionalidade_fk, cod_acao_fk, txt_login_inclusao)

    VALUES 
    ('1', '1', 1,'1'),
    ('1', '1', 2,'1'),
    ('1', '1', 3,'1'),
    ('1', '1', 4,'1'),
    ('1', '1', 5,'1'),

    ('1', '2', 1,'1'),
    ('1', '2', 2,'1'),
    ('1', '2', 3,'1'),
    ('1', '2', 4,'1'),
    ('1', '2', 5,'1'),

    ('1', '3', 1,'1'),
    ('1', '3', 2,'1'),
    ('1', '3', 3,'1'),
    ('1', '3', 4,'1'),
    ('1', '3', 5,'1'),

    ('1', '4', 1,'1'),
    ('1', '4', 2,'1'),
    ('1', '4', 3,'1'),
    ('1', '4', 4,'1'),
    ('1', '4', 5,'1'),

    ('1', '5', 1,'1'),
    ('1', '5', 2,'1'),
    ('1', '5', 3,'1'),
    ('1', '5', 4,'1'),
    ('1', '5', 5,'1'),

    ('1', '6', 1,'1'),
    ('1', '6', 2,'1'),
    ('1', '6', 3,'1'),
    ('1', '6', 4,'1'),
    ('1', '6', 5,'1'),


    ('1', '7', 1,'1'),
    ('1', '7', 2,'1'),
    ('1', '7', 3,'1'),
    ('1', '7', 4,'1'),
    ('1', '7', 5,'1'),


    ('2', '1', 1,'1'),
    ('2', '1', 2,'1'),
    ('2', '1', 3,'1'),
    ('2', '1', 4,'1'),
    ('2', '1', 5,'1'), 

    ('2', '2', 1,'1'),
    ('2', '2', 2,'1'),
    ('2', '2', 3,'1'),
    ('2', '2', 4,'1'),
    ('2', '2', 5,'1'), 

    ('2', '5', 1,'1'),
    ('2', '5', 2,'1'),
    ('2', '5', 3,'1'),
    ('2', '5', 4,'1'),
    ('2', '5', 5,'1'), 

    ('2', '6', 1,'1'),
    ('2', '6', 2,'1'),
    ('2', '6', 3,'1'),
    ('2', '6', 4,'1'),
    ('2', '6', 5,'1'), 


    ('2', '7', 1,'1'),
    ('2', '7', 2,'1'),
    ('2', '7', 3,'1'),
    ('2', '7', 4,'1'),
    ('2', '7', 5,'1'), 

    ('3', '3', 1,'1'),
    ('3', '3', 2,'1'),
    ('3', '3', 3,'1'),
    ('3', '3', 4,'1'),
    ('3', '3', 5,'1'),

    ('3', '4', 1,'1'),
    ('3', '4', 2,'1'),
    ('3', '4', 3,'1'),
    ('3', '4', 4,'1'),
    ('3', '4', 5,'1'),

   	('4', '1', 1,'1'),
    ('4', '2', 1,'1'),
    ('4', '3', 1,'1'),
    ('4', '4', 1,'1'),
    ('4', '5', 1,'1'),
    ('4', '6', 1,'1'),
    ('4', '7', 1,'1');


-------------------------------------Restrições do usuario
--retira as funcionalidades de deletar do usuario Coordenador dos modulos (mudulos, perfils, usuarios)
--retira as funcionalidades de updade e deletar do usuario Coordenador dos modulos (funcionalidades) e  e acoes deletar do modulo (acoes)

INSERT INTO acesso.tab_restricoes_usuarios(
            txt_login_inclusao, cod_usuario_fk, cod_perfil_funcionalidade_acao_fk)
    VALUES 
('1', '2',40),
('1', '2',45),
('1', '2',50),

(1, 3, 64),
(1, 3, 65),
(1, 3, 70);


---------------------------------------------------------------------
rollback;
--commit;
