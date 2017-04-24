<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "acesso.rlc_perfis_funcionalidades_acoes".
 *
 * @property integer $cod_perfil_funcionalidade_acao
 * @property integer $cod_perfil_fk
 * @property integer $cod_funcionalidade_fk
 * @property integer $cod_acao_fk
 * @property string $txt_login_inclusao
 * @property string $dte_inclusao
 * @property string $dte_alteracao
 * @property string $dte_exclusao
 *
 * @property TabAcoes $tabAcoes
 * @property TabFuncionalidades $tabFuncionalidades
 * @property TabPerfis $tabPerfis
 * @property TabRestricoesUsuarios[] $tabRestricoesUsuarios
 */
class RlcPerfisFuncionalidadesAcoes extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acesso.rlc_perfis_funcionalidades_acoes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod_perfil_fk', 'cod_funcionalidade_fk', 'cod_acao_fk'], 'required'],
            [['cod_perfil_fk', 'cod_funcionalidade_fk', 'cod_acao_fk'], 'integer'],
            [['dte_inclusao', 'dte_alteracao', 'dte_exclusao'], 'safe'],
            [['txt_login_inclusao'], 'string', 'max' => 150],
            [['cod_perfil_fk', 'cod_funcionalidade_fk', 'cod_acao_fk'], 'unique', 'targetAttribute' => ['cod_perfil_fk', 'cod_funcionalidade_fk', 'cod_acao_fk'], 'message' => 'The combination of Cod Perfil Fk, Cod Funcionalidade Fk and Cod Acao Fk has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_perfil_funcionalidade_acao' => 'Cod Perfil Funcionalidade Acao',
            'cod_perfil_fk' => 'Cod Perfil Fk',
            'cod_funcionalidade_fk' => 'Cod Funcionalidade Fk',
            'cod_acao_fk' => 'Cod Acao Fk',
            'txt_login_inclusao' => 'Usuário da Inclusão',
            'dte_inclusao' => 'Data da Inclusão',
            'dte_alteracao' => 'Data da Alteração',
            'dte_exclusao' => 'Data da Exclusão',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabAcoes()
    {
        return $this->hasOne(TabAcoes::className(), ['cod_acao' => 'cod_acao_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabFuncionalidades()
    {
        return $this->hasOne(TabFuncionalidades::className(), ['cod_funcionalidade' => 'cod_funcionalidade_fk']);
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
    public function getTabRestricoesUsuarios()
    {
        return $this->hasMany(TabRestricoesUsuarios::className(), ['cod_perfil_funcionalidade_acao_fk' => 'cod_perfil_funcionalidade_acao']);
    }

    /**
     * @inheritdoc
     * @return RlcPerfisFuncionalidadesAcoesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RlcPerfisFuncionalidadesAcoesQuery(get_called_class());
    }
}
