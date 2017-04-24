<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "public.tab_atributos".
 *
 * @property integer $cod_atributos
 * @property string $dsc_descricao
 * @property string $sgl_chave
 */
class TabAtributos extends \projeto\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'public.tab_atributos';
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
			[['dsc_descricao', 'sgl_chave'], 'string'],
			[['sgl_chave'], 'unique']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'cod_atributos' => 'Código',
			'dsc_descricao' => 'Descrição sucinta',
			'sgl_chave' => 'Chave única',
		];
	}

	/**
	 * @inheritdoc
	 * @return TabAtributosQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new TabAtributosQuery(get_called_class());
	}
}
