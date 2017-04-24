<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "dicionario.tab_form".
 *
 * @property integer $cod_form
 * @property integer $cod_tipo_servico
 * @property string $dsc_form
 * @property string $dsc_det_form
 * @property string $sgl_form
 *
 * @property TabAvisosErros[] $tabAvisosErros
 * @property TabModulos $tabModulos
 * @property TabGlossarios[] $tabGlossarios
 */
class TabForm extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dicionario.tab_form';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod_tipo_servico', 'dsc_form'], 'required'],
            [['cod_tipo_servico'], 'integer'],
            [['dsc_det_form'], 'string'],
            [['dsc_form'], 'string', 'max' => 120],
            [['sgl_form'], 'string', 'max' => 2],
			[['dte_inclusao', 'dte_alteracao', 'dte_exclusao'], 'safe'],
			[['txt_login_inclusao'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_form' => 'Cod Form',
            'cod_tipo_servico' => 'Cod Tipo Servico',
            'dsc_form' => 'Dsc Form',
            'dsc_det_form' => 'Dsc Det Form',
            'sgl_form' => 'Sgl Form',
			'dte_inclusao' => 'Dte Inclusao',
            'dte_alteracao' => 'Dte Alteracao',
            'dte_exclusao' => 'Dte Exclusao',
            'txt_login_inclusao' => 'Txt Login Inclusao',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabAvisosErros()
    {
        return $this->hasMany(TabAvisosErros::className(), ['fk_attr_formulario' => 'cod_form']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabModulos()
    {
        return $this->hasOne(TabModulos::className(), ['cod_modulo' => 'cod_tipo_servico']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabGlossarios()
    {
        return $this->hasMany(TabGlossarios::className(), ['fk_glossario_form' => 'cod_form']);
    }

    /**
     * @inheritdoc
     * @return TabFormQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabFormQuery(get_called_class());
    }
}
