<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabUsuariosPrestadoresSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tab-usuarios-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="barra-de-acoes-crud">
        <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Buscar', ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::resetButton('<i class="glyphicon glyphicon-refresh"></i> Resetar', ['class' => 'btn btn-default btn-sm']) ?>
    </div>

    <?= $form->field($model, 'cod_usuario') ?>

    <?= $form->field($model, 'txt_nome') ?>

    <?= $form->field($model, 'txt_email') ?>

    <?= $form->field($model, 'txt_senha') ?>

    <?= $form->field($model, 'num_fone') ?>

    <?php // echo $form->field($model, 'qtd_acesso') ?>

    <?php // echo $form->field($model, 'txt_trocar_senha') ?>

    <?php // echo $form->field($model, 'txt_ativo') ?>

    <?php // echo $form->field($model, 'txt_tipo_login') ?>

    <?php // echo $form->field($model, 'txt_login_inclusao') ?>

    <?php // echo $form->field($model, 'dte_inclusao') ?>

    <?php // echo $form->field($model, 'dte_alteracao') ?>

    <?php // echo $form->field($model, 'dte_exclusao') ?>

    <?php // echo $form->field($model, 'cod_prestador_fk') ?>


    <?php ActiveForm::end(); ?>

</div>
