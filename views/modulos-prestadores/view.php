<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\components\MenuLateralModuloWidget;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $model app\models\RlcModulosPrestadores */
?>

<?php

if ($model->rlcModulosPrestadores) {
	foreach ($model->rlcModulosPrestadores as $key => $value) {

		$tab[] = [
			'headerOptions'	 => ['id' => $value->tabModulos->id],
			'label'			 => $value->tabModulos->txt_nome,
			'content'		 => $this->render('_view_modulos', ['model' => $value]),
		];
	}
}
?>

<?=

TabsX::widget([
	'items'			 => $tab,
	'position'		 => TabsX::POS_ABOVE,
	'bordered'		 => true,
	'encodeLabels'	 => false,
	'options'		 => ['id' => 'contatos_info'],
]);
?>

 
