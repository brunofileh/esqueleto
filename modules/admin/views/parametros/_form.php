<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\TabParametros */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tab-parametros-form box box-default">
    <?php $form = ActiveForm::begin(); ?>

    <div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
			<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> '. ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')]) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Cancelar',  Url::toRoute($infoModulo['menu-item']['txt_url']), ['class' => 'btn btn-default btn-sm']) ?>
		</div>
    </div>
	
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'modulo_fk')->dropDownList(
					ArrayHelper::map(
						\app\modules\admin\models\TabModulos::find()->all(), 
						'cod_modulo', 
						'txt_nome'
					), [
						'prompt' => $this->app->params['txt-prompt-select'], 
						//'class' => 'chosen-select'
					]
				) ?>
			</div>
			<div class="col-md-6">
				<?= $form->field($model, 'num_ano_ref')->textInput() ?>

			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'sgl_parametro')->textInput(['maxlength' => true]) ?>
			</div>
			<div class="col-md-6">
				<?= $form->field($model, 'vlr_parametro')->textInput(['maxlength' => true]) ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<?= $form->field($model, 'dsc_parametro')->textarea(['rows' => 6]) ?>
			</div>
		</div>
    </div>

	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> '. ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')]) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Cancelar',  Url::toRoute($infoModulo['menu-item']['txt_url']), ['class' => 'btn btn-default btn-sm']) ?>
		</div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
