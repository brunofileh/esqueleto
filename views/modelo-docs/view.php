<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$infoModulo = $this->context->module->info;

?>

<div class="tab-modelo-docs-view box box-default">

    <div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
			<?= Html::a('<i class="glyphicon glyphicon-pencil"></i> Editar dados', ['update', 'id' => $model->cod_modelo_doc], ['class' => 'btn btn-primary btn-sm']) ?>
			<?= Html::a('<i class="glyphicon glyphicon-remove"></i> Excluir registro', ['delete', 'id' => $model->cod_modelo_doc], [
				'class' => 'btn btn-danger btn-sm',
				'data' => [
					'confirm' => 'Confirma a exclusão permanente deste registro?',
					'method' => 'post',
				],
			]) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Url::toRoute('index'), ['class' => 'btn btn-default btn-sm']) ?>
    	</div>
    </div>    
	
	<div class="box-body">
		<?= DetailView::widget([
			'model' => $model,
			'attributes' => [
				'txt_descricao',
				'sgl_id',
				'dsc_tipo_modelo_documento', 
				'dsc_cabecalho', 
				'dsc_rodape', 
				'dsc_finalidade', 
				'dte_inclusao:date',
				'dte_alteracao:date',
				'dte_exclusao:date',
				'txt_login_inclusao',
				'txt_conteudo:html',
			],
		]) ?>
	</div>    
	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			<?= Html::a('<i class="glyphicon glyphicon-pencil"></i> Editar dados', ['update', 'id' => $model->cod_modelo_doc], ['class' => 'btn btn-primary btn-sm']) ?>
			<?= Html::a('<i class="glyphicon glyphicon-remove"></i> Excluir registro', ['delete', 'id' => $model->cod_modelo_doc], [
				'class' => 'btn btn-danger btn-sm',
				'data' => [
					'confirm' => 'Confirma a exclusão permanente deste registro?',
					'method' => 'post',
				],
			]) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Url::toRoute('index'), ['class' => 'btn btn-default btn-sm']) ?>
		</div>
    </div>
</div>
