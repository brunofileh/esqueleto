<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "tab_parametros".
 *
 * @property integer $cod_parametro
 * @property string $modulo_fk
 * @property integer $num_ano_ref
 * @property string $sgl_parametro
 * @property string $vlr_parametro
 * @property string $dsc_parametro
 * @property string $dte_inclusao
 * @property string $dte_alteracao
 * @property string $dte_exclusao
 * @property string $txt_login_inclusao
 *
 * @property TabModulos $tabModulos
 */
class TabParametros extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tab_parametros';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['modulo_fk', 'num_ano_ref'], 'integer'],
            [['num_ano_ref', 'sgl_parametro'], 'required'],
            [['dsc_parametro'], 'string'],
            [['dte_inclusao', 'dte_alteracao', 'dte_exclusao'], 'safe'],
            [['sgl_parametro'], 'string', 'max' => 50],
            [['vlr_parametro'], 'string'],
            [['txt_login_inclusao'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_parametro' => 'Cod Parametro',
            'modulo_fk' => 'Modulo Fk',
            'num_ano_ref' => 'Num Ano Ref',
            'sgl_parametro' => 'Sgl Parametro',
            'vlr_parametro' => 'Vlr Parametro',
            'dsc_parametro' => 'Dsc Parametro',
            'dte_inclusao' => 'Dte Inclusao',
            'dte_alteracao' => 'Dte Alteracao',
            'dte_exclusao' => 'Dte Exclusao',
            'txt_login_inclusao' => 'Txt Login Inclusao',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabModulos()
    {
        return $this->hasOne(TabModulos::className(), ['cod_modulo' => 'modulo_fk']);
    }

    /**
     * @inheritdoc
     * @return TabParametrosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabParametrosQuery(get_called_class());
    }
}
