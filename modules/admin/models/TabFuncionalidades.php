<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "acesso.tab_funcionalidades".
 *
 * @property integer $cod_funcionalidade
 * @property string $txt_nome
 * @property string $dsc_funcionalidade
 * @property string $txt_login_inclusao
 * @property string $dte_inclusao
 * @property string $dte_alteracao
 * @property string $dte_exclusao
 *
 * @property RlcPerfisFuncionalidadesAcoes[] $rlcPerfisFuncionalidadesAcoes
 * @property TabMenus[] $tabMenus
 */
class TabFuncionalidades extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acesso.tab_funcionalidades';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['txt_nome'], 'required'],
            [['dte_inclusao', 'dte_alteracao', 'dte_exclusao'], 'safe'],
            [['txt_nome'], 'string', 'max' => 80],
            [['dsc_funcionalidade', 'txt_login_inclusao'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_funcionalidade' => 'Cod Funcionalidade',
            'txt_nome' => 'Nome',
            'dsc_funcionalidade' => 'Descrição',
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
        return $this->hasMany(RlcPerfisFuncionalidadesAcoes::className(), ['cod_funcionalidade_fk' => 'cod_funcionalidade']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabMenus()
    {
        return $this->hasMany(TabMenus::className(), ['cod_perfil_funcionalidade_acao_fk' => 'cod_funcionalidade']);
    }

    /**
     * @inheritdoc
     * @return TabFuncionalidadesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabFuncionalidadesQuery(get_called_class());
    }
}
