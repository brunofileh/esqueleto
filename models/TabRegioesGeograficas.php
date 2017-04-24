<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "public.tab_regioes_geograficas".
 *
 * @property integer $cod_regiao_geografica
 * @property string $sgl_regiao_geografica
 * @property string $txt_nome
 */
class TabRegioesGeograficas extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'public.tab_regioes_geograficas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod_regiao_geografica', 'sgl_regiao_geografica', 'txt_nome'], 'required'],
            [['cod_regiao_geografica'], 'integer'],
            [['sgl_regiao_geografica', 'txt_nome'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_regiao_geografica' => 'Código da Região Geográfica',
            'sgl_regiao_geografica' => 'Sigla',
            'txt_nome' => 'Nome da Região Geográfica',
        ];
    }

    /**
     * @inheritdoc
     * @return TabRegioesGeograficasQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabRegioesGeograficasQuery(get_called_class());
    }
}
