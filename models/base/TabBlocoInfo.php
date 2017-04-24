<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "dicionario.tab_bloco_info".
 *
 * @property integer $cod_bloco_info
 * @property string $dsc_titulo_bloco
 * @property integer $num_ordem_bloco
 * @property string $sgl_id
 * @property string $servico_fk
 * @property string $dte_inclusao
 * @property string $dte_alteracao
 * @property string $dte_exclusao
 * @property string $txt_login_inclusao
 * @property string $txt_icone
 * @property integer $fk_form
 *
 * @property TabModulos $tabModulos
 * @property TabForm $tabForm
 * @property TabGlossarios[] $tabGlossarios
 */
class TabBlocoInfo extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dicionario.tab_bloco_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dsc_titulo_bloco', 'sgl_id', 'servico_fk'], 'required'],
            [['num_ordem_bloco', 'servico_fk', 'fk_form'], 'integer'],
            [['dte_inclusao', 'dte_alteracao', 'dte_exclusao'], 'safe'],
            [['dsc_titulo_bloco'], 'string', 'max' => 120],
            [['sgl_id'], 'string', 'max' => 40],
            [['txt_login_inclusao'], 'string', 'max' => 150],
            [['txt_icone'], 'string', 'max' => 30],
            [['sgl_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_bloco_info' => 'Cod Bloco Info',
            'dsc_titulo_bloco' => 'Dsc Titulo Bloco',
            'num_ordem_bloco' => 'Num Ordem Bloco',
            'sgl_id' => 'Sgl ID',
            'servico_fk' => 'Servico Fk',
            'dte_inclusao' => 'Dte Inclusao',
            'dte_alteracao' => 'Dte Alteracao',
            'dte_exclusao' => 'Dte Exclusao',
            'txt_login_inclusao' => 'Txt Login Inclusao',
            'txt_icone' => 'Txt Icone',
            'fk_form' => 'Fk Form',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabModulos()
    {
        return $this->hasOne(TabModulos::className(), ['cod_modulo' => 'servico_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabForm()
    {
        return $this->hasOne(TabForm::className(), ['cod_form' => 'fk_form']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabGlossarios()
    {
        return $this->hasMany(TabGlossarios::className(), ['fk_glossario_bloco_info' => 'cod_bloco_info']);
    }

    /**
     * @inheritdoc
     * @return TabBlocoInfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabBlocoInfoQuery(get_called_class());
    }
}
