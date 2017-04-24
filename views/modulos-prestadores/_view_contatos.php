<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\models\TabAtributosSearch;
use app\models\TabAtributosValoresSearch;
/* @var $this yii\web\View */
/* @var $model app\models\RlcModulosPrestadores */
?>
<?=
DetailView::widget([
	'model'		 => $model,
	'attributes' => [
		"txt_{$tipo}_nome",
		"txt_{$tipo}_cargo",
		[
			'attribute'	 => "txt_{$tipo}_genero",
			'value'		 => TabAtributosValoresSearch::getDescricaoAtributoValor(TabAtributosSearch::TIPO_GENERO, $model->{"txt_{$tipo}_genero"})
		,
		],
		[
			'attribute'	 => "txt_{$tipo}_tratamento",
			'value'		 => TabAtributosValoresSearch::getDescricaoAtributoValor(TabAtributosSearch::PRONOME_TRATAMENTO, $model->{"txt_{$tipo}_tratamento"})
		,
		],
		[
			'attribute'	 => "txt_{$tipo}_partido",
			'value'		 => TabAtributosValoresSearch::getDescricaoAtributoValor(TabAtributosSearch::PARTIDO_POLITICO, $model->{"txt_{$tipo}_partido"})
		,
		],
		[
			'attribute'	 => "txt_{$tipo}_sucessao",
			'value'		 => TabAtributosValoresSearch::getDescricaoAtributoValor(TabAtributosSearch::SUCESSAO_CARGO_POLITICO, $model->{"txt_{$tipo}_sucessao"})
		,
		],
		"txt_{$tipo}_email:email",
		"txt_{$tipo}_email2:email",
		"num_{$tipo}_fone",
		"num_{$tipo}_fone2",
		"num_{$tipo}_fone3",
	],
]);
?>
