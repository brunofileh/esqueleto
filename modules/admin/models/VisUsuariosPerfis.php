<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "acesso.vis_usuarios_perfis".
 *
 * @property string $nome_usuario
 * @property string $txt_email
 * @property string $txt_senha
 * @property string $num_fone
 * @property integer $qtd_acesso
 * @property string $txt_trocar_senha
 * @property string $txt_ativo
 * @property string $txt_tipo_login
 * @property string $num_ip
 * @property string $dte_sessao
 * @property string $txt_login
 * @property string $num_cpf
 * @property string $cod_prestador_fk
 * @property string $nome_perfil
 * @property string $dsc_perfil
 * @property string $nome_modulo
 * @property string $dsc_modulo
 * @property string $cod_usuario_fk
 * @property string $cod_perfil_fk
 * @property string $cod_modulo_fk
 * @property string $modulo_id
 * @property string $modulo_url
 * @property string $modulo_icone
 * @property string $txt_perfil_prestador
 */
class VisUsuariosPerfis extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acesso.vis_usuarios_perfis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qtd_acesso', 'cod_prestador_fk', 'cod_usuario_fk', 'cod_perfil_fk', 'cod_modulo_fk'], 'integer'],
            [['dte_sessao'], 'safe'],
            [['nome_usuario'], 'string', 'max' => 70],
            [['txt_email', 'dsc_perfil', 'dsc_modulo'], 'string', 'max' => 150],
            [['txt_senha'], 'string', 'max' => 60],
            [['num_fone', 'num_ip'], 'string', 'max' => 15],
            [['txt_trocar_senha', 'txt_ativo', 'txt_tipo_login', 'txt_perfil_prestador'], 'string', 'max' => 1],
            [['txt_login', 'modulo_icone'], 'string', 'max' => 100],
            [['num_cpf'], 'string', 'max' => 14],
            [['nome_perfil', 'nome_modulo'], 'string', 'max' => 80],
            [['modulo_id'], 'string', 'max' => 40],
            [['modulo_url'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nome_usuario' => 'Nome Usuario',
            'txt_email' => 'Txt Email',
            'txt_senha' => 'Txt Senha',
            'num_fone' => 'Num Fone',
            'qtd_acesso' => 'Qtd Acesso',
            'txt_trocar_senha' => 'Txt Trocar Senha',
            'txt_ativo' => 'Txt Ativo',
            'txt_tipo_login' => 'Txt Tipo Login',
            'num_ip' => 'Num Ip',
            'dte_sessao' => 'Dte Sessao',
            'txt_login' => 'Txt Login',
            'num_cpf' => 'Num Cpf',
            'cod_prestador_fk' => 'Cod Prestador Fk',
            'nome_perfil' => 'Nome Perfil',
            'dsc_perfil' => 'Dsc Perfil',
            'nome_modulo' => 'Nome Modulo',
            'dsc_modulo' => 'Dsc Modulo',
            'cod_usuario_fk' => 'Cod Usuario Fk',
            'cod_perfil_fk' => 'Cod Perfil Fk',
            'cod_modulo_fk' => 'Cod Modulo Fk',
            'modulo_id' => 'Modulo ID',
            'modulo_url' => 'Modulo Url',
            'modulo_icone' => 'Modulo Icone',
            'txt_perfil_prestador' => 'Txt Perfil Prestador',
        ];
    }

    /**
     * @inheritdoc
     * @return VisUsuariosPerfisQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VisUsuariosPerfisQuery(get_called_class());
    }
}
