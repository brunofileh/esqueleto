<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabMenusSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tab-menus-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="barra-de-acoes-crud">
        <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Buscar', ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::resetButton('<i class="glyphicon glyphicon-refresh"></i> Resetar', ['class' => 'btn btn-default btn-sm']) ?>
    </div>

    <?= $form->field($model, 'cod_menu') ?>

    <?= $form->field($model, 'txt_nome') ?>

    <?= $form->field($model, 'dsc_menu') ?>

    <?= $form->field($model, 'txt_url') ?>

    <?= $form->field($model, 'txt_imagem') ?>

    <?php // echo $form->field($model, 'num_ordem') ?>

    <?php // echo $form->field($model, 'num_nivel') ?>

    <?php // echo $form->field($model, 'cod_perfil_funcionalidade_acao_fk') ?>

    <?php // echo $form->field($model, 'cod_menu_fk') ?>

    <?php // echo $form->field($model, 'txt_login_inclusao') ?>

    <?php // echo $form->field($model, 'dte_inclusao') ?>

    <?php // echo $form->field($model, 'dte_alteracao') ?>


    <?php ActiveForm::end(); ?>

</div>
