<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\MenuLateralModuloWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Usuarios */

$infoModulo = $this->context->module->info;

?>

<div class="usuarios-admin">
	<?=
	$this->render('_form', [
		'model' => $model,
		'infoModulo' => $infoModulo,
		'listaPerfil' => $listaPerfil,
		'listaModulo' => $listaModulo,
	])
	?>

	
</div>
