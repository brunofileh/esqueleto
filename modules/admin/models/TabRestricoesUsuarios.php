<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "acesso.tab_restricoes_usuarios".
 *
 * @property integer $cod_restricao_usuario
 * @property integer $cod_usuario_fk
 * @property integer $cod_perfil_funcionalidade_acao_fk
 * @property string $txt_login_inclusao
 * @property string $dte_inclusao
 * @property string $dte_alteracao
 * @property string $dte_exclusao
 *
 * @property RlcPerfisFuncionalidadesAcoes $rlcPerfisFuncionalidadesAcoes
 * @property TabUsuarios $tabUsuarios
 */
class TabRestricoesUsuarios extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acesso.tab_restricoes_usuarios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod_usuario_fk', 'cod_perfil_funcionalidade_acao_fk'], 'required'],
            [['cod_usuario_fk', 'cod_perfil_funcionalidade_acao_fk'], 'integer'],
            [['dte_inclusao', 'dte_alteracao', 'dte_exclusao'], 'safe'],
            [['txt_login_inclusao'], 'string', 'max' => 150],
            [['cod_usuario_fk', 'cod_perfil_funcionalidade_acao_fk'], 'unique', 'targetAttribute' => ['cod_usuario_fk', 'cod_perfil_funcionalidade_acao_fk'], 'message' => 'The combination of Cod Usuario Fk and Cod Perfil Funcionalidade Acao Fk has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_restricao_usuario' => 'Cod Restricao Usuario',
            'cod_usuario_fk' => 'Cod Usuario Fk',
            'cod_perfil_funcionalidade_acao_fk' => 'Cod Perfil Funcionalidade Acao Fk',
            'txt_login_inclusao' => 'Usuário da Inclusão',
            'dte_inclusao' => 'Data da Inclusão',
            'dte_alteracao' => 'Data da Alteração',
            'dte_exclusao' => 'Data da Exclusão',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRlcPerfisFuncionalidadesAcoes()
    {
        return $this->hasOne(RlcPerfisFuncionalidadesAcoes::className(), ['cod_perfil_funcionalidade_acao' => 'cod_perfil_funcionalidade_acao_fk']);
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
     * @return TabRestricoesUsuariosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabRestricoesUsuariosQuery(get_called_class());
    }
}
