<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\MenuLateralModuloWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Funcionalidades */

$infoModulo = $this->context->module->info;

?>

<div class="funcionalidades-create">
    <?= $this->render('_form', [
        'model' => $model,
		'modulo' => $modulo,
        'infoModulo' => $infoModulo,
		'listaMenus' => $listaMenus,
    ]) ?>
	
</div>
