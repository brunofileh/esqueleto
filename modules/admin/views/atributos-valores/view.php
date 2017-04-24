<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\components\MenuLateralModuloWidget;
/* @var $this yii\web\View */
/* @var $model app\models\TabAtributosValores */

$infoModulo = $this->context->module->info;
$linkBack = Url::toRoute(['index', 'TabAtributosValoresSearch[fk_atributos_valores_atributos_id]' => $model->fk_atributos_valores_atributos_id]);

?>

<?php  $this->beginBlock('conteudo-principal') ?>
<div class="tab-atributos-valores-view box box-default">

	<div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
		<?= Html::a('<i class="glyphicon glyphicon-pencil"></i> Editar dados', ['update', 'id' => $model->cod_atributos_valores], ['class' => 'btn btn-primary btn-sm']) ?>
		<?= Html::a('<i class="glyphicon glyphicon-remove"></i> Excluir registro', ['delete', 'id' => $model->cod_atributos_valores], [
			'class' => 'btn btn-danger btn-sm',
			'data' => [
				'confirm' => 'Confirma a exclusão permanente deste registro?',
				'method' => 'post',
			],
		]) ?>
		<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', $linkBack, ['class' => 'btn btn-default btn-sm']) ?>
		</div>
	</div>	
	
	<div class="box-body">
	<?= DetailView::widget([
		'model' => $model,
		'attributes' => [
			'cod_atributos_valores',
			'fk_atributos_valores_atributos_id',
			'sgl_valor',
			'dsc_descricao',
		],
	]) ?>
	</div>	
	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
		<?= Html::a('<i class="glyphicon glyphicon-pencil"></i> Editar dados', ['update', 'id' => $model->cod_atributos_valores], ['class' => 'btn btn-primary btn-sm']) ?>
		<?= Html::a('<i class="glyphicon glyphicon-remove"></i> Excluir registro', ['delete', 'id' => $model->cod_atributos_valores], [
			'class' => 'btn btn-danger btn-sm',
			'data' => [
				'confirm' => 'Confirma a exclusão permanente deste registro?',
				'method' => 'post',
			],
		]) ?>
		<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', $linkBack, ['class' => 'btn btn-default btn-sm']) ?>
		</div>
	</div>
</div>
<?php  $this->endBlock() ?>
