<?php
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\TabMenusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\modules\admin\models\TabMenusSearch;

$infoModulo = $this->context->module->info;

$filter_nome	  = TabMenusSearch::getListaPorAtributo('txt_nome', $modulo['cod_modulo']);
$filter_descricao = TabMenusSearch::getListaPorAtributo('dsc_menu', $modulo['cod_modulo']);
?>

<div class="menus-index box box-default">

	<div class="box-header with-border">
		<h3 class="box-title">Consulta</h3>
		<div class="box-tools pull-right">
			<?= Html::a('<i class="glyphicon glyphicon-plus"></i> Incluir novo registro', Url::to(['admin', 'cod_modulo' => $modulo['cod_modulo']]), ['class' => 'btn btn-success btn-sm']) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Url::toRoute(['/admin/modulos', 'cod_modulo'=>$modulo['cod_modulo'] ]), ['class' => 'btn btn-default btn-sm']) ?>
		</div>
	</div>

	<div class="box-body with-border">
		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel'  => $searchModel,
			'columns'	  => [
				[
					'attribute' => 'txt_nome',
					'content'   => function($data) {
						return TabMenusSearch::getTextoPorAtributo('txt_nome', $data->txt_nome);
					},
					'filter' => $filter_nome,
				],
				[
					'attribute' => 'dsc_menu',
					'content'   => function($data) {
						return TabMenusSearch::getTextoPorAtributo('dsc_menu', $data->dsc_menu);
					},
					'filter' => $filter_descricao,
				],
				[
					'attribute' => 'txt_url',
					'content' => function ($model) {
						return Html::a($model->txt_url, Url::toRoute($model->txt_url));
					}
				],
				'num_ordem',
				[
					'class'	=> 'projeto\grid\ActionColumn',
					'template' => '{view} {admin} {delete}',
					'buttons'  => [
						'admin' => function ($action, $model, $key) {
							return Html::a('<span class="fa fa-edit"></span>', Url::to(['menus/admin', 'id' => $key, 'cod_modulo' => $model->rlcMenusPerfis[0]->tabPerfis->cod_modulo_fk]), [
								'title'	  => 'Alterar',
								'aria-label' => 'Alterar',
								'data-pjax'  => '0',
								'data-toggle' => 'tooltip',
							]);
						},
						'view' => function ($action, $model, $key) {
							return Html::a('<span class="fa fa-eye"> </span>', Url::to(['menus/view', 'id' => $model->cod_menu, 'cod_modulo' => $model->rlcMenusPerfis[0]->tabPerfis->cod_modulo_fk]), [
								'title' => 'Exibir',
								'data-toggle' => 'tooltip'
							]);
						},
						'delete' => function($action, $model, $key) {
							return Html::a('<span class="fa fa-trash"> </span>', Url::to(['menus/delete', 'id' => $model->cod_menu]), [
								'data-confirm' => 'Confirma a exclusão permanente deste registro?',
								'data-method'  => 'post',
								'title' => 'Excluir',
								'data-toggle' => 'tooltip'
							]);
						},
					]
				],
			],
		]) ?>

		</div>
		<div class="box-footer">
			<h3 class="box-title"></h3>
			<div class="box-tools pull-right">
				<?= Html::a('<i class="glyphicon glyphicon-plus"></i> Incluir novo registro', Url::to(['admin', 'cod_modulo' => $modulo['cod_modulo']]), ['class' => 'btn btn-success btn-sm']) ?>
				<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Url::toRoute(['/admin/modulos', 'cod_modulo'=>$modulo['cod_modulo'] ]), ['class' => 'btn btn-default btn-sm']) ?>
			</div>
		</div>
	</div>
