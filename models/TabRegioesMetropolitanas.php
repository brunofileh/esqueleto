<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "public.tab_regioes_metropolitanas".
 *
 * @property integer $cod_regiao_metropolitana
 * @property string $txt_nome
 */
class TabRegioesMetropolitanas extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'public.tab_regioes_metropolitanas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['txt_nome'], 'required'],
            [['txt_nome'], 'string', 'max' => 2000],
            [['txt_nome'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_regiao_metropolitana' => 'Código da região metropolitana',
            'txt_nome' => 'Nome da Região Metropolitana',
        ];
    }

    /**
     * @inheritdoc
     * @return TabRegioesMetropolitanasQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabRegioesMetropolitanasQuery(get_called_class());
    }
}
