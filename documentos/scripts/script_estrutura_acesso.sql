drop schema acesso cascade;

create schema acesso;


CREATE TABLE acesso.tab_sessao_php (
                id CHAR(40) NOT NULL,
                expire INTEGER,
                data BYTEA,
                CONSTRAINT tab_sessao_php_pkey PRIMARY KEY (id)
);


CREATE SEQUENCE acesso.tab_usuarios_cod_usuario_seq;

CREATE TABLE acesso.tab_usuarios (
                cod_usuario BIGINT NOT NULL DEFAULT nextval('acesso.tab_usuarios_cod_usuario_seq'),
                txt_nome VARCHAR(70) NOT NULL,
                txt_email VARCHAR(150) NOT NULL,
                txt_senha VARCHAR(60) NOT NULL,
                num_fone VARCHAR(15),
                qtd_acesso INTEGER,
                txt_trocar_senha CHAR(1),
                txt_ativo CHAR(1) NOT NULL,
                txt_tipo_login CHAR(1) NOT NULL,
                txt_login_inclusao VARCHAR(150),
                dte_inclusao TIMESTAMP DEFAULT now(),
                dte_alteracao TIMESTAMP,
                cod_prestador_fk BIGINT,
                CONSTRAINT tab_usuarios_pkey PRIMARY KEY (cod_usuario)
);
COMMENT ON COLUMN acesso.tab_usuarios.txt_nome IS 'Nome';
COMMENT ON COLUMN acesso.tab_usuarios.txt_email IS 'Email';
COMMENT ON COLUMN acesso.tab_usuarios.txt_senha IS 'Senha';
COMMENT ON COLUMN acesso.tab_usuarios.num_fone IS 'Telefone';
COMMENT ON COLUMN acesso.tab_usuarios.qtd_acesso IS 'Quantidade de Acesso';
COMMENT ON COLUMN acesso.tab_usuarios.txt_trocar_senha IS 'Trocar Senha';
COMMENT ON COLUMN acesso.tab_usuarios.txt_tipo_login IS 'Tipo de Login';
COMMENT ON COLUMN acesso.tab_usuarios.txt_login_inclusao IS 'Usuário da Inclusão';
COMMENT ON COLUMN acesso.tab_usuarios.dte_inclusao IS 'Data da Inclusão';
COMMENT ON COLUMN acesso.tab_usuarios.dte_alteracao IS 'Data da Alteração';
COMMENT ON COLUMN acesso.tab_usuarios.cod_prestador_fk IS 'Código do Prestador';


ALTER SEQUENCE acesso.tab_usuarios_cod_usuario_seq OWNED BY acesso.tab_usuarios.cod_usuario;

CREATE UNIQUE INDEX usuario_email_idx
 ON acesso.tab_usuarios
 ( txt_email );

CREATE SEQUENCE acesso.tab_modulos_cod_modulo_seq;

CREATE TABLE acesso.tab_modulos (
                cod_modulo BIGINT NOT NULL DEFAULT nextval('acesso.tab_modulos_cod_modulo_seq'),
                txt_nome VARCHAR(80) NOT NULL,
                id VARCHAR(40),
                dsc_modulo VARCHAR(150),
                txt_url VARCHAR(200),
                txt_icone VARCHAR(100),
                txt_login_inclusao VARCHAR(150),
                dte_inclusao TIMESTAMP DEFAULT now(),
                dte_alteracao TIMESTAMP,
                CONSTRAINT tab_modulos_pkey PRIMARY KEY (cod_modulo)
);
COMMENT ON COLUMN acesso.tab_modulos.txt_nome IS 'Nome';
COMMENT ON COLUMN acesso.tab_modulos.dsc_modulo IS 'Descrição';
COMMENT ON COLUMN acesso.tab_modulos.txt_url IS 'URL';
COMMENT ON COLUMN acesso.tab_modulos.txt_icone IS 'Ícone';
COMMENT ON COLUMN acesso.tab_modulos.txt_login_inclusao IS 'Usuário da Inclusão';
COMMENT ON COLUMN acesso.tab_modulos.dte_inclusao IS 'Data da Inclusão';
COMMENT ON COLUMN acesso.tab_modulos.dte_alteracao IS 'Data da Alteração';


ALTER SEQUENCE acesso.tab_modulos_cod_modulo_seq OWNED BY acesso.tab_modulos.cod_modulo;

CREATE SEQUENCE acesso.tab_perfis_cod_perfil_seq;

CREATE TABLE acesso.tab_perfis (
                cod_perfil BIGINT NOT NULL DEFAULT nextval('acesso.tab_perfis_cod_perfil_seq'),
                txt_nome VARCHAR(80) NOT NULL,
                dsc_perfil VARCHAR(150),
                cod_modulo_fk BIGINT NOT NULL,
                txt_perfil_prestador character(1) NOT NULL DEFAULT 0,
                txt_login_inclusao VARCHAR(150),
                dte_inclusao TIMESTAMP DEFAULT now(),
                dte_alteracao TIMESTAMP,
                dte_exclusao timestamp without time zone,
                CONSTRAINT tab_perfis_pkey PRIMARY KEY (cod_perfil)
);
COMMENT ON COLUMN acesso.tab_perfis.txt_nome IS 'Nome';
COMMENT ON COLUMN acesso.tab_perfis.dsc_perfil IS 'Descrição';
COMMENT ON COLUMN acesso.tab_perfis.txt_login_inclusao IS 'Usuário da Inclusão';
COMMENT ON COLUMN acesso.tab_perfis.dte_inclusao IS 'Data da Inclusão';
COMMENT ON COLUMN acesso.tab_perfis.dte_alteracao IS 'Data da Alteração';


ALTER SEQUENCE acesso.tab_perfis_cod_perfil_seq OWNED BY acesso.tab_perfis.cod_perfil;

CREATE SEQUENCE acesso.rlc_usuarios_perfis_cod_usuario_perfil_seq;

CREATE TABLE acesso.rlc_usuarios_perfis (
                cod_usuario_perfil BIGINT NOT NULL DEFAULT nextval('acesso.rlc_usuarios_perfis_cod_usuario_perfil_seq'),
                cod_usuario_fk BIGINT NOT NULL,
                cod_perfil_fk BIGINT NOT NULL,
                txt_login_inclusao VARCHAR(150),
                dte_inclusao TIMESTAMP DEFAULT now(),
                dte_alteracao TIMESTAMP,
                CONSTRAINT rlc_usuarios_perfis_pkey PRIMARY KEY (cod_usuario_perfil)
);
COMMENT ON COLUMN acesso.rlc_usuarios_perfis.txt_login_inclusao IS 'Usuário da Inclusão';
COMMENT ON COLUMN acesso.rlc_usuarios_perfis.dte_inclusao IS 'Data da Inclusão';
COMMENT ON COLUMN acesso.rlc_usuarios_perfis.dte_alteracao IS 'Data da Alteração';


ALTER SEQUENCE acesso.rlc_usuarios_perfis_cod_usuario_perfil_seq OWNED BY acesso.rlc_usuarios_perfis.cod_usuario_perfil;

CREATE UNIQUE INDEX idx_rlc_usuarios_perfis_cod_perfil_fk_cod_usuario_fk
 ON acesso.rlc_usuarios_perfis
 ( cod_usuario_fk, cod_perfil_fk );

CREATE SEQUENCE acesso.tab_funcionalidades_cod_funcionalidade_seq;

CREATE TABLE acesso.tab_funcionalidades (
                cod_funcionalidade BIGINT NOT NULL DEFAULT nextval('acesso.tab_funcionalidades_cod_funcionalidade_seq'),
                txt_nome VARCHAR(80) NOT NULL,
                dsc_funcionalidade VARCHAR(150),
                txt_login_inclusao VARCHAR(150),
                dte_inclusao TIMESTAMP DEFAULT now(),
                dte_alteracao TIMESTAMP,
                CONSTRAINT tab_funcionalidades_pkey PRIMARY KEY (cod_funcionalidade)
);
COMMENT ON COLUMN acesso.tab_funcionalidades.txt_nome IS 'Nome';
COMMENT ON COLUMN acesso.tab_funcionalidades.dsc_funcionalidade IS 'Descrição';
COMMENT ON COLUMN acesso.tab_funcionalidades.txt_login_inclusao IS 'Usuário da Inclusão';
COMMENT ON COLUMN acesso.tab_funcionalidades.dte_inclusao IS 'Data da Inclusão';
COMMENT ON COLUMN acesso.tab_funcionalidades.dte_alteracao IS 'Data da Alteração';


ALTER SEQUENCE acesso.tab_funcionalidades_cod_funcionalidade_seq OWNED BY acesso.tab_funcionalidades.cod_funcionalidade;

CREATE SEQUENCE acesso.tab_acoes_cod_acao_seq;

CREATE TABLE acesso.tab_acoes (
                cod_acao BIGINT NOT NULL DEFAULT nextval('acesso.tab_acoes_cod_acao_seq'),
                txt_nome VARCHAR(45) NOT NULL,
                dsc_acao VARCHAR(150),
                txt_login_inclusao VARCHAR(150),
                dte_inclusao TIMESTAMP,
                dte_alteracao TIMESTAMP,
                CONSTRAINT tab_acoes_pkey PRIMARY KEY (cod_acao)
);
COMMENT ON COLUMN acesso.tab_acoes.txt_nome IS 'Nome';
COMMENT ON COLUMN acesso.tab_acoes.dsc_acao IS 'Descrição';
COMMENT ON COLUMN acesso.tab_acoes.txt_login_inclusao IS 'Usuário da Inclusão';
COMMENT ON COLUMN acesso.tab_acoes.dte_inclusao IS 'Data da Inclusão';
COMMENT ON COLUMN acesso.tab_acoes.dte_alteracao IS 'Data da Alteração';


ALTER SEQUENCE acesso.tab_acoes_cod_acao_seq OWNED BY acesso.tab_acoes.cod_acao;

CREATE SEQUENCE acesso.rlc_perfis_funcionalidades_acoes_cod_perfil_funcionalidade_a281;

CREATE TABLE acesso.rlc_perfis_funcionalidades_acoes (
                cod_perfil_funcionalidade_acao BIGINT NOT NULL DEFAULT nextval('acesso.rlc_perfis_funcionalidades_acoes_cod_perfil_funcionalidade_a281'),
                cod_perfil_fk BIGINT NOT NULL,
                cod_funcionalidade_fk BIGINT NOT NULL,
                cod_acao_fk BIGINT NOT NULL,
                txt_login_inclusao VARCHAR(150),
                dte_inclusao TIMESTAMP,
                dte_alteracao TIMESTAMP,
                CONSTRAINT rlc_perfis_funcionalidades_acoes_pkey PRIMARY KEY (cod_perfil_funcionalidade_acao)
);
COMMENT ON COLUMN acesso.rlc_perfis_funcionalidades_acoes.txt_login_inclusao IS 'Usuário da Inclusão';
COMMENT ON COLUMN acesso.rlc_perfis_funcionalidades_acoes.dte_inclusao IS 'Data da Inclusão';
COMMENT ON COLUMN acesso.rlc_perfis_funcionalidades_acoes.dte_alteracao IS 'Data da Alteração';


ALTER SEQUENCE acesso.rlc_perfis_funcionalidades_acoes_cod_perfil_funcionalidade_a281 OWNED BY acesso.rlc_perfis_funcionalidades_acoes.cod_perfil_funcionalidade_acao;

CREATE UNIQUE INDEX idx_rlc_perfis_funcionalidades_acoes_cod_perfil_cod_funcinal922
 ON acesso.rlc_perfis_funcionalidades_acoes
 ( cod_perfil_fk, cod_funcionalidade_fk, cod_acao_fk );

CREATE SEQUENCE acesso.tab_restricoes_usuarios_cod_restricao_usuario_seq;

CREATE TABLE acesso.tab_restricoes_usuarios (
                cod_restricao_usuario BIGINT NOT NULL DEFAULT nextval('acesso.tab_restricoes_usuarios_cod_restricao_usuario_seq'),
                cod_usuario_fk BIGINT NOT NULL,
                cod_perfil_funcionalidade_acao_fk BIGINT NOT NULL,
                txt_login_inclusao VARCHAR(150),
                dte_inclusao TIMESTAMP DEFAULT now(),
                dte_alteracao TIMESTAMP,
                CONSTRAINT tab_restricoes_usuarios_pkey PRIMARY KEY (cod_restricao_usuario)
);
COMMENT ON COLUMN acesso.tab_restricoes_usuarios.txt_login_inclusao IS 'Usuário da Inclusão';
COMMENT ON COLUMN acesso.tab_restricoes_usuarios.dte_inclusao IS 'Data da Inclusão';
COMMENT ON COLUMN acesso.tab_restricoes_usuarios.dte_alteracao IS 'Data da Alteração';


ALTER SEQUENCE acesso.tab_restricoes_usuarios_cod_restricao_usuario_seq OWNED BY acesso.tab_restricoes_usuarios.cod_restricao_usuario;

CREATE UNIQUE INDEX idx_tab_restricoes_usuarios_cod_usuario_fk_cod_perfil_funcio43
 ON acesso.tab_restricoes_usuarios
 ( cod_usuario_fk, cod_perfil_funcionalidade_acao_fk );

CREATE SEQUENCE acesso.tab_menus_cod_menu_seq;

CREATE TABLE acesso.tab_menus (
                cod_menu BIGINT NOT NULL DEFAULT nextval('acesso.tab_menus_cod_menu_seq'),
                txt_nome VARCHAR(80) NOT NULL,
                dsc_menu VARCHAR(150),
                txt_url VARCHAR(100),
                txt_imagem VARCHAR(100),
                num_ordem INTEGER,
                num_nivel INTEGER,
                cod_perfil_funcionalidade_acao_fk BIGINT,
                cod_menu_fk BIGINT,
                txt_login_inclusao VARCHAR(150),
                dte_inclusao TIMESTAMP DEFAULT now(),
                dte_alteracao TIMESTAMP,
                CONSTRAINT tab_menus_pkey PRIMARY KEY (cod_menu)
);
COMMENT ON COLUMN acesso.tab_menus.txt_nome IS 'Nome';
COMMENT ON COLUMN acesso.tab_menus.dsc_menu IS 'Descrição';
COMMENT ON COLUMN acesso.tab_menus.txt_url IS 'URL';
COMMENT ON COLUMN acesso.tab_menus.txt_imagem IS 'Imagem';
COMMENT ON COLUMN acesso.tab_menus.num_ordem IS 'Ordem';
COMMENT ON COLUMN acesso.tab_menus.num_nivel IS 'Nível';
COMMENT ON COLUMN acesso.tab_menus.cod_menu_fk IS 'Menu Pai';
COMMENT ON COLUMN acesso.tab_menus.txt_login_inclusao IS 'Usuário da Inclusão';
COMMENT ON COLUMN acesso.tab_menus.dte_inclusao IS 'Data da Inclusão';
COMMENT ON COLUMN acesso.tab_menus.dte_alteracao IS 'Data da Alteração';


ALTER SEQUENCE acesso.tab_menus_cod_menu_seq OWNED BY acesso.tab_menus.cod_menu;

CREATE SEQUENCE acesso.rlc_menus_perfis_cod_menu_perfil_seq;

CREATE TABLE acesso.rlc_menus_perfis (
                cod_menu_perfil BIGINT NOT NULL DEFAULT nextval('acesso.rlc_menus_perfis_cod_menu_perfil_seq'),
                cod_perfil_fk BIGINT NOT NULL,
                cod_menu_fk BIGINT NOT NULL,
                txt_login_inclusao VARCHAR(150),
                dte_inclusao TIMESTAMP DEFAULT now(),
                dte_alteracao TIMESTAMP,
                CONSTRAINT rlc_menus_perfis_pkey PRIMARY KEY (cod_menu_perfil)
);
COMMENT ON COLUMN acesso.rlc_menus_perfis.txt_login_inclusao IS 'Usuário da Inclusão';
COMMENT ON COLUMN acesso.rlc_menus_perfis.dte_inclusao IS 'Data da Inclusão';
COMMENT ON COLUMN acesso.rlc_menus_perfis.dte_alteracao IS 'Data da Alteração';


ALTER SEQUENCE acesso.rlc_menus_perfis_cod_menu_perfil_seq OWNED BY acesso.rlc_menus_perfis.cod_menu_perfil;

CREATE UNIQUE INDEX inx_rlc_menus_perfils_cod_perfil_fk_cod_menu_fk
 ON acesso.rlc_menus_perfis
 ( cod_perfil_fk, cod_menu_fk );

ALTER TABLE acesso.tab_restricoes_usuarios ADD CONSTRAINT fk_tab_usuarios_tab_restricoes_usuarios
FOREIGN KEY (cod_usuario_fk)
REFERENCES acesso.tab_usuarios (cod_usuario)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE acesso.rlc_usuarios_perfis ADD CONSTRAINT fk_rlc_usuarios_perfis_tab_usuarios
FOREIGN KEY (cod_usuario_fk)
REFERENCES acesso.tab_usuarios (cod_usuario)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE acesso.tab_perfis ADD CONSTRAINT fk_tab_perfis_tab_modulos
FOREIGN KEY (cod_modulo_fk)
REFERENCES acesso.tab_modulos (cod_modulo)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE acesso.rlc_menus_perfis ADD CONSTRAINT fk_rlc_menus_perfis_tab_perfis
FOREIGN KEY (cod_perfil_fk)
REFERENCES acesso.tab_perfis (cod_perfil)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE acesso.rlc_usuarios_perfis ADD CONSTRAINT fk_rlc_usuarios_perfis_tab_perfis
FOREIGN KEY (cod_perfil_fk)
REFERENCES acesso.tab_perfis (cod_perfil)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE acesso.rlc_perfis_funcionalidades_acoes ADD CONSTRAINT fk_rlc_perfis_funcionalidades_acoes_tab_perfis
FOREIGN KEY (cod_perfil_fk)
REFERENCES acesso.tab_perfis (cod_perfil)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE acesso.rlc_perfis_funcionalidades_acoes ADD CONSTRAINT fk_rlc_perfis_funcionalidades_acoes_tab_funcionalidades
FOREIGN KEY (cod_funcionalidade_fk)
REFERENCES acesso.tab_funcionalidades (cod_funcionalidade)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE acesso.rlc_perfis_funcionalidades_acoes ADD CONSTRAINT fk_rlc_perfis_funcionalidades_acoes_tab_acoes
FOREIGN KEY (cod_acao_fk)
REFERENCES acesso.tab_acoes (cod_acao)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE acesso.tab_menus ADD CONSTRAINT fk_tab_menus_rlc_perfis_funcionalidades_acoes
FOREIGN KEY (cod_perfil_funcionalidade_acao_fk)
REFERENCES acesso.tab_funcionalidades (cod_funcionalidade)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE acesso.tab_restricoes_usuarios ADD CONSTRAINT fk_tab_restricoes_usuarios_rlc_perfis_funcionalidades_acoes
FOREIGN KEY (cod_perfil_funcionalidade_acao_fk)
REFERENCES acesso.rlc_perfis_funcionalidades_acoes (cod_perfil_funcionalidade_acao)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE acesso.tab_menus ADD CONSTRAINT fk_tab_menus_tab_menus
FOREIGN KEY (cod_menu_fk)
REFERENCES acesso.tab_menus (cod_menu)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE acesso.rlc_menus_perfis ADD CONSTRAINT fk_rlc_menu_perfil_tab_menu
FOREIGN KEY (cod_menu_fk)
REFERENCES acesso.tab_menus (cod_menu)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE acesso.tab_usuarios ADD CONSTRAINT fk_tab_usuarios_tab_prestadores
FOREIGN KEY (cod_prestador_fk)
REFERENCES public.tab_prestadores (cod_prestador)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;