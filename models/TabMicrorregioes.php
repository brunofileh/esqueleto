<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "public.tab_microrregioes".
 *
 * @property integer $cod_microrregiao
 * @property integer $fk_mesorregiao
 * @property string $txt_nome
 */
class TabMicrorregioes extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'public.tab_microrregioes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod_microrregiao', 'fk_mesorregiao', 'txt_nome'], 'required'],
            [['cod_microrregiao', 'fk_mesorregiao'], 'integer'],
            [['txt_nome'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_microrregiao' => 'Código Microregiao',
            'fk_mesorregiao' => 'fk_mesorregiao',
            'txt_nome' => 'Nome',
        ];
    }

    /**
     * @inheritdoc
     * @return TabMicrorregioesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabMicrorregioesQuery(get_called_class());
    }
}
