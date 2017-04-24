<?php

namespace app\models;

use Yii;
use kartik\tabs\TabsX;
use app\models\VisGlossarios;
use app\models\TabForm;

class TabBlocoInfo extends base\TabBlocoInfo
{
	public static $blocosTabsx = [];
	
	public function attributeLabels()
	{
		return [
			'cod_bloco_info' => 'Código',
			'dsc_titulo_bloco' => 'Título',
			'num_ordem_bloco' => 'Ordem',
			'sgl_id' => 'Identificador',
			'servico_fk' => 'Serviço',
		];
	}

	public static function initBlocos(array $blocos)
	{
		$getBlocos = function () use ($blocos) {
			return TabBlocoInfo::find()
				->select(['sgl_id', 'dsc_titulo_bloco', 'num_ordem_bloco', 'txt_icone'])
				->where(['sgl_id' => $blocos])
				->indexBy('sgl_id')
				->orderBy('num_ordem_bloco')
				->asArray()
				->all()
			;
		};
		
		if (Yii::$app->params['habilitar-cache-global']) {
			static::$blocosTabsx = TabBlocoInfo::getDb()->cache(function ($db) use ($getBlocos) {
				return $getBlocos();
			});	
		}
		else {
			static::$blocosTabsx = $getBlocos();
		}
	}

	public static function tabsx($bloco, $content, $br=true)
	{
		$label = static::$blocosTabsx[$bloco]['dsc_titulo_bloco'];
		if (isset(static::$blocosTabsx[$bloco]['txt_icone'])) {
			$label = '<i class="'. static::$blocosTabsx[$bloco]['txt_icone'] .'"></i> ' . $label;
		}

		return TabsX::widget([
			'items' => [
				[
					'label'   => "<b style=\"color:#337ab7\">$label</b>",
					'content' => $content,
					'active'  => false,
				]
			],
			'position'	 => TabsX::POS_ABOVE,
			'bordered'	 => true,
			'encodeLabels' => false,
		]) . ($br ? '' : '');
	}
	
	public static function getInfoBlocosByForm($sglForm)
	{
		$informacoes = [];
		$codForm = TabForm::find()->select('cod_form')->where(['sgl_form' => $sglForm])->column()[0];
		$blocos = TabBlocoInfo::find()->where(['fk_form' => $codForm])->asArray()->orderBy('num_ordem_bloco')->all();
		
		foreach ($blocos as $bloco) {
			$informacoes[$bloco['cod_bloco_info']] = VisGlossarios::find()
				->where([
					'fk_glossario_bloco_info' => $bloco['cod_bloco_info'],
					'bln_info_ativa' => true,
					'num_ano_ref' => Yii::$app->params['ano-ref'],
				])
				->asArray()
				->orderBy('num_ordem')
				->all()
			;
		}
		
		return [$blocos, $informacoes];
	}
	
	public static function getInfoBlocosByForm2($sglForm)
	{
		$codForm = TabForm::find()->select('cod_form')->where(['sgl_form' => $sglForm])->column()[0];
		
		return VisGlossarios::find()
			->where([
				'fk_glossario_form' => $codForm,
				'num_ano_ref' => Yii::$app->params['ano-ref'],
			])
			->asArray()
			->orderBy('num_ordem, sgl_cod_info')
			->indexBy('sgl_cod_info')
			->all()
		;
	}	
}