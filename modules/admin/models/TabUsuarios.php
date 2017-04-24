<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "acesso.tab_usuarios".
 *
 * @property integer $cod_usuario
 * @property string $txt_nome
 * @property string $txt_email
 * @property string $txt_senha
 * @property string $num_fone
 * @property integer $qtd_acesso
 * @property string $txt_trocar_senha
 * @property string $txt_ativo
 * @property string $txt_tipo_login
 * @property string $txt_login_inclusao
 * @property string $dte_inclusao
 * @property string $dte_alteracao
 * @property string $dte_exclusao
 * @property integer $cod_prestador_fk
 * @property string $txt_imagem
 * @property string $txt_login
 * @property string $num_cpf
 * @property string $num_ip
 * @property string $dte_sessao
 *
 * @property RlcUsuariosPerfis[] $rlcUsuariosPerfis
 * @property TabRestricoesUsuarios[] $tabRestricoesUsuarios
 * @property TabPrestadores $tabPrestadores
 */
class TabUsuarios extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acesso.tab_usuarios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['txt_nome', 'txt_email', 'txt_senha', 'txt_ativo', 'txt_tipo_login'], 'required'],
            [['qtd_acesso', 'cod_prestador_fk'], 'integer'],
            [['dte_inclusao', 'dte_alteracao', 'dte_exclusao', 'dte_sessao'], 'safe'],
            [['txt_nome'], 'string', 'max' => 70],
            [['txt_email', 'txt_login_inclusao'], 'string', 'max' => 150],
            [['txt_senha'], 'string', 'max' => 60],
            [['num_fone', 'num_ip'], 'string', 'max' => 15],
            [['txt_trocar_senha', 'txt_ativo', 'txt_tipo_login'], 'string', 'max' => 1],
            [['txt_imagem', 'txt_login'], 'string', 'max' => 100],
            [['num_cpf'], 'string', 'max' => 14],
            [['txt_email'], 'unique'],
            [['txt_login'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_usuario' => 'Cod Usuario',
            'txt_nome' => 'Nome',
            'txt_email' => 'Email',
            'txt_senha' => 'Senha',
            'num_fone' => 'Telefone',
            'qtd_acesso' => 'Quantidade de Acesso',
            'txt_trocar_senha' => 'Trocar Senha',
            'txt_ativo' => 'Txt Ativo',
            'txt_tipo_login' => 'Tipo de Login',
            'txt_login_inclusao' => 'Usuário da Inclusão',
            'dte_inclusao' => 'Data da Inclusão',
            'dte_alteracao' => 'Data da Alteração',
            'dte_exclusao' => 'Data da Exclusão',
            'cod_prestador_fk' => 'Código do Prestador',
            'txt_imagem' => 'Imagem',
            'txt_login' => 'Login',
            'num_cpf' => 'CPF',
            'num_ip' => 'Ip que o usuario loga no sistema',
            'dte_sessao' => 'data/hora que o usuario loga no sistema',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRlcUsuariosPerfis()
    {
        return $this->hasMany(RlcUsuariosPerfis::className(), ['cod_usuario_fk' => 'cod_usuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabRestricoesUsuarios()
    {
        return $this->hasMany(TabRestricoesUsuarios::className(), ['cod_usuario_fk' => 'cod_usuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabPrestadores()
    {
        return $this->hasOne(TabPrestadores::className(), ['cod_prestador' => 'cod_prestador_fk']);
    }

    /**
     * @inheritdoc
     * @return TabUsuariosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabUsuariosQuery(get_called_class());
    }
}
