<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\MenuLateralModuloWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Menus */

$infoModulo = $this->context->module->info;

?>

<div class="menus-admin">

	<?=
	$this->render( '_form' , [
		'model'			 => $model ,
		'modulo'		 => $modulo ,
		'listaPerfis'	 => $listaPerfis ,
		'listaMenusPai'	 => $listaMenusPai ,
		'infoModulo'	 => $infoModulo ,
	] )
	?>

</div>
