<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "acesso.tab_acoes".
 *
 * @property integer $cod_acao
 * @property string $txt_nome
 * @property string $dsc_acao
 * @property string $txt_login_inclusao
 * @property string $dte_inclusao
 * @property string $dte_alteracao
 * @property string $dte_exclusao
 *
 * @property RlcPerfisFuncionalidadesAcoes[] $rlcPerfisFuncionalidadesAcoes
 */
class TabAcoes extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acesso.tab_acoes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['txt_nome'], 'required'],
            [['dte_inclusao', 'dte_alteracao', 'dte_exclusao'], 'safe'],
            [['txt_nome'], 'string', 'max' => 45],
            [['dsc_acao', 'txt_login_inclusao'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_acao' => 'Cod Acao',
            'txt_nome' => 'Nome',
            'dsc_acao' => 'Descrição',
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
        return $this->hasMany(RlcPerfisFuncionalidadesAcoes::className(), ['cod_acao_fk' => 'cod_acao']);
    }

    /**
     * @inheritdoc
     * @return TabAcoesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabAcoesQuery(get_called_class());
    }
}
