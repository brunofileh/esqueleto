<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use app\models\VisAtributosValores;
use kartik\select2\Select2;
use projeto\Util;

$infoModule = $this->context->module->info;
$model->modulo_fk = $infoModule['cod_modulo'];

$cabecalhos = array_merge(['' => $this->app->params['txt-prompt-select']], VisAtributosValores::getOpcoes('cabecalho-modelo-documento'));
$rodapes = array_merge(['' => $this->app->params['txt-prompt-select']], VisAtributosValores::getOpcoes('rodape-modelo-documento'));
$finalidades = array_merge(['' => $this->app->params['txt-prompt-select']], VisAtributosValores::getOpcoes('finalidade-modelo-documento'));
$tipoDocs = array_merge(['' => $this->app->params['txt-prompt-select']], VisAtributosValores::getOpcoes('tipo-modelo-documento'));

?>

<div class="tab-modelo-docs-form box box-default">
    <?php $form = ActiveForm::begin(); ?>

    <div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
			<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> '. ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')]) ?>
			<?php if (!$model->isNewRecord): ?>
				<?= Html::a('<i class="fa fa-file-pdf-o"></i> Visualizar',  Url::to(['print', 'id' => $model->cod_modelo_doc]), ['class' => 'btn btn-default btn-sm']) ?>
			<?php endif ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Cancelar',  Url::toRoute('index'), ['class' => 'btn btn-default btn-sm']) ?>
		</div>
    </div>
	
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'sgl_id')->textInput(['maxlength' => true, 'disabled' => !$model->isNewRecord]) ?>
				<?= $form->field($model, 'txt_descricao')->textInput(['maxlength' => true]) ?>
				<?= $form->field($model, 'finalidade_fk')->dropDownList($finalidades) ?>
				<?= $form->field($model, 'modulo_fk')->hiddenInput()->label(false); ?>
			</div>
			<div class="col-md-6">
				<?= $form->field($model, 'tipo_modelo_documento_fk')->dropDownList($tipoDocs) ?>
				<?= $form->field($model, 'cabecalho_fk')->dropDownList($cabecalhos) ?>
				<?= $form->field($model, 'rodape_fk')->dropDownList($rodapes) ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<?= $form->field($model, 'txt_conteudo')->widget(CKEditor::className(), [
					'options' => ['rows' => 6],
					'preset' => 'full'
				]) ?>
			</div>
		</div>
    </div>

	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> '. ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')]) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Cancelar',  Url::toRoute(Url::toRoute('index')), ['class' => 'btn btn-default btn-sm']) ?>
		</div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
