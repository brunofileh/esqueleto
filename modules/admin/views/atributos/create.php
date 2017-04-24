<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\MenuLateralModuloWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Atributos */

$infoModulo = $this->context->module->info;

?>

<div class="atributos-create">

    <?= $this->render('_form', [
        'model' => $model,
        'infoModulo' => $infoModulo,
    ]) ?>
	
</div>
