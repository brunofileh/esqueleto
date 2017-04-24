<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabPerfisSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tab-perfis-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="barra-de-acoes-crud">
        <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Buscar', ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::resetButton('<i class="glyphicon glyphicon-refresh"></i> Resetar', ['class' => 'btn btn-default btn-sm']) ?>
    </div>

    <?= $form->field($model, 'cod_perfil') ?>

    <?= $form->field($model, 'txt_nome') ?>

    <?= $form->field($model, 'dsc_perfil') ?>

    <?= $form->field($model, 'cod_modulo_fk') ?>

    <?= $form->field($model, 'txt_login_inclusao') ?>

    <?php // echo $form->field($model, 'dte_inclusao') ?>

    <?php // echo $form->field($model, 'dte_alteracao') ?>


    <?php ActiveForm::end(); ?>

</div>
