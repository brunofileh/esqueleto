<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\MenuLateralModuloWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabAcoes */

$infoModulo = $this->context->module->info;
?>

<div class="tab-acoes-update">
<?php  $this->beginBlock('conteudo-principal') ?>
 
    <?= $this->render('_form', [
        'model' => $model,
        'infoModulo' => $infoModulo,
    ]) ?>
<?php  $this->endBlock() ?>
</div>
