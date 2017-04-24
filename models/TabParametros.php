<?php

namespace app\models;

use Yii;
use app\modules\admin\models\TabModulos;

class TabParametros extends base\TabParametros
{
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'cod_parametro' => 'Código',
			'modulo_fk' => 'Módulo',
			'num_ano_ref' => 'Ano',
			'sgl_parametro' => 'Sigla',
			'vlr_parametro' => 'Valor',
			'dsc_parametro' => 'Descrição', 
			'dte_inclusao' => 'Dt inclusão',
			'dte_alteracao' => 'Dt alteração',
			'dte_exclusao' => 'Dt exclusão',
			'txt_login_inclusao' => 'Login inclusão',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTabModulos()
	{
		return $this->hasOne(TabModulos::className(), ['cod_modulo' => 'modulo_fk']);
	}
	
	public function afterSave($insert, $changedAttributes) {
		$r = parent::afterSave($insert, $changedAttributes);
		Yii::$app->cache->delete('parametros-do-sistema');
		return $r;
	}
	
	public function afterDelete() {
		$r = parent::afterDelete();
		Yii::$app->cache->delete('parametros-do-sistema');
		return $r;
	}
}
