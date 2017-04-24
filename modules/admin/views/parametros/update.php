<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\MenuLateralModuloWidget;

/* @var $this yii\web\View */
/* @var $model app\models\TabParametros */

$infoModulo = $this->context->module->info;

?>

<div class="tab-parametros-update">
    <?= $this->render('_form', [
        'model' => $model,
        'infoModulo' => $infoModulo,
    ]) ?>
</div>
