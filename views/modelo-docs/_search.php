<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TabModeloDocsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tab-modelo-docs-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="barra-de-acoes-crud">
        <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Buscar', ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::resetButton('<i class="glyphicon glyphicon-refresh"></i> Resetar', ['class' => 'btn btn-default btn-sm']) ?>
    </div>

    <?= $form->field($model, 'cod_modelo_doc') ?>

    <?= $form->field($model, 'modulo_fk') ?>

    <?= $form->field($model, 'sgl_id') ?>

    <?= $form->field($model, 'txt_descricao') ?>

    <?= $form->field($model, 'txt_conteudo') ?>

    <?php // echo $form->field($model, 'dte_inclusao') ?>

    <?php // echo $form->field($model, 'dte_alteracao') ?>

    <?php // echo $form->field($model, 'dte_exclusao') ?>

    <?php // echo $form->field($model, 'txt_login_inclusao') ?>


    <?php ActiveForm::end(); ?>

</div>
