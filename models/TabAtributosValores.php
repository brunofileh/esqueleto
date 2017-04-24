<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "public.tab_atributos_valores".
 *
 * @property integer $cod_atributos_valores
 * @property integer $fk_atributos_valores_atributos_id
 * @property string $sgl_valor
 * @property string $dsc_descricao
 */
class TabAtributosValores extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'public.tab_atributos_valores';
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        VisAtributosValores::atualizar();
    }
    
    public function afterDelete()
    {
        VisAtributosValores::atualizar();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fk_atributos_valores_atributos_id', 'sgl_valor', 'dsc_descricao'], 'required'],
            [['fk_atributos_valores_atributos_id'], 'integer'],
            [['sgl_valor'], 'string'],
            [['dsc_descricao'], 'string', 'max' => 200],
            [['fk_atributos_valores_atributos_id', 'sgl_valor', 'dsc_descricao'], 'safe'],
            ['sgl_valor', 'unique', 'targetAttribute' => ['sgl_valor', 'fk_atributos_valores_atributos_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_atributos_valores' => 'Código da tabela',
            'fk_atributos_valores_atributos_id' => 'Atributo pai',
            'sgl_valor' => 'Identificador único',
            'dsc_descricao' => 'Descrição',
        ];
    }

    /**
     * @inheritdoc
     * @return TabAtributosValoresQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabAtributosValoresQuery(get_called_class());
    }
}
