<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "acesso.rlc_usuarios_perfis".
 *
 * @property integer $cod_usuario_perfil
 * @property integer $cod_usuario_fk
 * @property integer $cod_perfil_fk
 * @property string $txt_login_inclusao
 * @property string $dte_inclusao
 * @property string $dte_alteracao
 * @property string $dte_exclusao
 * @property integer $qtd_acesso
 * @property string $txt_sessao
 * @property string $txt_tipo_login
 *
 * @property TabPerfis $tabPerfis
 * @property TabUsuarios $tabUsuarios
 */
class RlcUsuariosPerfis extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acesso.rlc_usuarios_perfis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod_usuario_fk', 'cod_perfil_fk'], 'required'],
            [['cod_usuario_fk', 'cod_perfil_fk', 'qtd_acesso'], 'integer'],
            [['dte_inclusao', 'dte_alteracao', 'dte_exclusao'], 'safe'],
            [['txt_login_inclusao'], 'string', 'max' => 150],
            [['txt_sessao'], 'string', 'max' => 40],
            [['txt_tipo_login'], 'string', 'max' => 1],
            [['cod_usuario_fk', 'cod_perfil_fk'], 'unique', 'targetAttribute' => ['cod_usuario_fk', 'cod_perfil_fk'], 'message' => 'The combination of Cod Usuario Fk and Cod Perfil Fk has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_usuario_perfil' => 'Cod Usuario Perfil',
            'cod_usuario_fk' => 'Cod Usuario Fk',
            'cod_perfil_fk' => 'Cod Perfil Fk',
            'txt_login_inclusao' => 'Usuário da Inclusão',
            'dte_inclusao' => 'Data da Inclusão',
            'dte_alteracao' => 'Data da Alteração',
            'dte_exclusao' => 'Data da Exclusão',
            'qtd_acesso' => 'Quantidade de acesso por perfil',
            'txt_sessao' => 'Id da sessão do usuário',
            'txt_tipo_login' => 'Tipo de Login',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabPerfis()
    {
        return $this->hasOne(TabPerfis::className(), ['cod_perfil' => 'cod_perfil_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabUsuarios()
    {
        return $this->hasOne(TabUsuarios::className(), ['cod_usuario' => 'cod_usuario_fk']);
    }

    /**
     * @inheritdoc
     * @return RlcUsuariosPerfisQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RlcUsuariosPerfisQuery(get_called_class());
    }
}
