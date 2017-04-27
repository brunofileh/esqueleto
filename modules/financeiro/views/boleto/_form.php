<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\financeiro\models\TabBoleto */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tab-boleto-form box box-default">
    <?php $form = ActiveForm::begin(); ?>

    <div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
			<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> '. ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')]) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Cancelar',  Yii::$app->request->referrer, ['class' => 'btn btn-default btn-sm']) ?>
		</div>
    </div>
	
	<div class="box-body">
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'nu_documento')->textInput(['maxlength' => true]) ?>
    </div>
</div>
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'dt_vencimento')->widget(
						\dosamigos\datepicker\DatePicker::className(), [
						'language' => 'pt-BR',
						'clientOptions' => [
						'autoclose' => true,
						'format' => 'dd/mm/yyyy'
					]
				]); ?>
    </div>
</div>
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'ds_valor')->textInput()->widget(\kartik\money\MaskMoney::className(), [
																			'pluginOptions' => [
																			'thousands' => '.',
																			'decimal' => ',',
																			'precision' => 2,
																			'allowZero' => false,]
																			]); ?>
    </div>
</div>
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'cod_tipo_contrato_fk')->dropDownList(
								ArrayHelper::map(
												app\modules\financeiro\models\TabTipoContrato::find()->all(), 
												'cod_tipo_contrato', 
												'txt_nome'
												),
								['prompt' => $this->app->params['txt-prompt-select'], 
								'class' => 'chosen-select'
							]); ?>
    </div>
</div>
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'nu_doc')->textInput(['maxlength' => true]) ?>
    </div>
</div>
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'dt_inclusao')->widget(
						\dosamigos\datepicker\DatePicker::className(), [
						'language' => 'pt-BR',
						'clientOptions' => [
						'autoclose' => true,
						'format' => 'dd/mm/yyyy'
					]
				]); ?>
    </div>
</div>
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'nosso_numero')->textInput(['maxlength' => true]) ?>
    </div>
</div>
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'valor_multa')->textInput()->widget(\kartik\money\MaskMoney::className(), [
																			'pluginOptions' => [
																			'thousands' => '.',
																			'decimal' => ',',
																			'precision' => 2,
																			'allowZero' => false,]
																			]); ?>
    </div>
</div>
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'multa')->widget(
						\dosamigos\datepicker\DatePicker::className(), [
						'language' => 'pt-BR',
						'clientOptions' => [
						'autoclose' => true,
						'format' => 'dd/mm/yyyy'
					]
				]); ?>
    </div>
</div>
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'valor_juros')->textInput(['maxlength' => true]) ?>
    </div>
</div>
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'dt_pagamento')->widget(
						\dosamigos\datepicker\DatePicker::className(), [
						'language' => 'pt-BR',
						'clientOptions' => [
						'autoclose' => true,
						'format' => 'dd/mm/yyyy'
					]
				]); ?>
    </div>
</div>
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'fic_comp')->textInput(['maxlength' => true]) ?>
    </div>
</div>
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'fic_comp2')->textInput(['maxlength' => true]) ?>
    </div>
</div>
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'advogado')->checkbox() ?>
    </div>
</div>
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'valor_pago')->textInput()->widget(\kartik\money\MaskMoney::className(), [
																			'pluginOptions' => [
																			'thousands' => '.',
																			'decimal' => ',',
																			'precision' => 2,
																			'allowZero' => false,]
																			]); ?>
    </div>
</div>
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'ativo')->checkbox() ?>
    </div>
</div>
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'dt_processamento')->widget(
						\dosamigos\datepicker\DatePicker::className(), [
						'language' => 'pt-BR',
						'clientOptions' => [
						'autoclose' => true,
						'format' => 'dd/mm/yyyy'
					]
				]); ?>
    </div>
</div>
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'dt_ocorrencia')->widget(
						\dosamigos\datepicker\DatePicker::className(), [
						'language' => 'pt-BR',
						'clientOptions' => [
						'autoclose' => true,
						'format' => 'dd/mm/yyyy'
					]
				]); ?>
    </div>
</div>
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'dt_exclusao')->widget(
						\dosamigos\datepicker\DatePicker::className(), [
						'language' => 'pt-BR',
						'clientOptions' => [
						'autoclose' => true,
						'format' => 'dd/mm/yyyy'
					]
				]); ?>
    </div>
</div>
    <div class='row'>
<div class='col-lg-6'>
    <?= $form->field($model, 'justificativa_exclusao')->textarea(['rows' => 6]) ?>
    </div>
</div>
    </div>

	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> '. ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')]) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Cancelar',  Yii::$app->request->referrer, ['class' => 'btn btn-default btn-sm']) ?>
		</div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
