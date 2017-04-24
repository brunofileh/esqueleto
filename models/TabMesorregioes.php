<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "public.tab_mesorregioes".
 *
 * @property integer $cod_mesorregiao
 * @property integer $cod_estado_fk
 * @property string $txt_nome
 */
class TabMesorregioes extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'public.tab_mesorregioes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod_mesorregiao', 'cod_estado_fk', 'txt_nome'], 'required'],
            [['cod_mesorregiao', 'cod_estado_fk'], 'integer'],
            [['txt_nome'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_mesorregiao' => 'Código Mesoregião',
            'cod_estado_fk' => 'Código do Estado',
            'txt_nome' => 'Nome',
        ];
    }

    /**
     * @inheritdoc
     * @return TabMesorregioesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabMesorregioesQuery(get_called_class());
    }
}
