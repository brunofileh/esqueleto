<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TabBlocoInfoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tab-bloco-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="barra-de-acoes-crud">
        <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Buscar', ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::resetButton('<i class="glyphicon glyphicon-refresh"></i> Resetar', ['class' => 'btn btn-default btn-sm']) ?>
    </div>

    <?= $form->field($model, 'cod_bloco_info') ?>

    <?= $form->field($model, 'dsc_titulo_bloco') ?>

    <?= $form->field($model, 'num_ordem_bloco') ?>

    <?= $form->field($model, 'sgl_id') ?>

    <?= $form->field($model, 'servico_fk') ?>


    <?php ActiveForm::end(); ?>

</div>
