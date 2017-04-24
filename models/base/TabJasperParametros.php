<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "public.tab_jasper_parametros".
 *
 * @property integer $id
 * @property string $sgl_relid
 * @property string $cod_prestador_fk
 */
class TabJasperParametros extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'public.tab_jasper_parametros';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sgl_relid', 'cod_prestador_fk'], 'required'],
            [['cod_prestador_fk'], 'integer'],
            [['sgl_relid'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sgl_relid' => 'Prestador',
            'cod_prestador_fk' => 'Cod Prestador Fk',
        ];
    }

    /**
     * @inheritdoc
     * @return TabJasperParametrosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabJasperParametrosQuery(get_called_class());
    }
}
