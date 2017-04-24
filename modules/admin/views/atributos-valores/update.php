<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\MenuLateralModuloWidget;

/* @var $this yii\web\View */
/* @var $model app\models\TabAtributosValores */

$infoModulo = $this->context->module->info;
$linkBack = Url::toRoute(['index', 'TabAtributosValoresSearch[fk_atributos_valores_atributos_id]' => $model->fk_atributos_valores_atributos_id]);

?>

<div class="tab-atributos-valores-update">
<?php  $this->beginBlock('conteudo-principal') ?>
    <?= $this->render('_form', [
        'model' => $model,
        'infoModulo' => $infoModulo,
        'linkBack' => $linkBack,
    ]) ?>
<?php  $this->endBlock() ?>
</div>
