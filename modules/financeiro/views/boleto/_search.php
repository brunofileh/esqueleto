<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\financeiro\models\TabBoletoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tab-boleto-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="barra-de-acoes-crud">
        <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Buscar', ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::resetButton('<i class="glyphicon glyphicon-refresh"></i> Resetar', ['class' => 'btn btn-default btn-sm']) ?>
    </div>

    <?= $form->field($model, 'cod_boleto') ?>

    <?= $form->field($model, 'nu_documento') ?>

    <?= $form->field($model, 'dt_vencimento') ?>

    <?= $form->field($model, 'ds_valor') ?>

    <?= $form->field($model, 'cod_tipo_contrato_fk') ?>

    <?php // echo $form->field($model, 'nu_doc') ?>

    <?php // echo $form->field($model, 'dt_inclusao') ?>

    <?php // echo $form->field($model, 'nosso_numero') ?>

    <?php // echo $form->field($model, 'valor_multa') ?>

    <?php // echo $form->field($model, 'multa') ?>

    <?php // echo $form->field($model, 'valor_juros') ?>

    <?php // echo $form->field($model, 'dt_pagamento') ?>

    <?php // echo $form->field($model, 'fic_comp') ?>

    <?php // echo $form->field($model, 'fic_comp2') ?>

    <?php // echo $form->field($model, 'advogado')->checkbox() ?>

    <?php // echo $form->field($model, 'valor_pago') ?>

    <?php // echo $form->field($model, 'ativo')->checkbox() ?>

    <?php // echo $form->field($model, 'dt_processamento') ?>

    <?php // echo $form->field($model, 'dt_ocorrencia') ?>

    <?php // echo $form->field($model, 'dt_exclusao') ?>

    <?php // echo $form->field($model, 'justificativa_exclusao') ?>


    <?php ActiveForm::end(); ?>

</div>
