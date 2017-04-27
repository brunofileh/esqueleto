<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\MenuLateralModuloWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\financeiro\models\Boleto */

$infoModulo = $this->context->module->info;

?>

<div class="boleto-admin">
 
    <?= $this->render('_form', [
        'model' => $model,
        'infoModulo' => $infoModulo,
    ]) ?>
	
</div>
