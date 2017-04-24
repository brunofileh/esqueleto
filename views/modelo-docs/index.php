<?php
/* @var $this yii\web\View */
/* @var $searchModel app\models\TabModeloDocsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\components\MenuLateralModuloWidget;

$infoModulo = $this->context->module->info;

?>

<div class="modelo-docs-index box box-default">

	<div class="box-header with-border">
		<h3 class="box-title">Consulta</h3>
		<div class="box-tools pull-right">
			 <?= Html::a('<i class="glyphicon glyphicon-plus"></i> Incluir novo registro', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
		</div>
	</div>

	<div class="box-body with-border">
		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'columns' => [
				'dte_alteracao:datetime',
				'txt_descricao',
				'sgl_id',
				'txt_login_inclusao',
				['class' => 'projeto\grid\ActionColumn'],
			],
		]); ?>
	</div>
	
	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			 <?= Html::a('<i class="glyphicon glyphicon-plus"></i> Incluir novo registro', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
		</div>
	</div>
</div>


