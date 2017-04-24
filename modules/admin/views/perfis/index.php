<?php
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\TabPerfisSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\modules\admin\models\TabPerfisSearch;

$infoModulo = $this->context->module->info;

$filter_nome		 = TabPerfisSearch::getListaPorAtributo('txt_nome', $modulo['cod_modulo']);
$filter_descricao	 = TabPerfisSearch::getListaPorAtributo('dsc_perfil', $modulo['cod_modulo']);
?>


<div class="box box-default">

	<div class="box-header with-border">
		<h3 class="box-title">Consulta</h3>
		<div class="box-tools pull-right">
			<?= Html::a('<i class="glyphicon glyphicon-plus"></i> Incluir novo registro', Url::to(['admin', 'cod_modulo' => $modulo['cod_modulo']]), [ 'class' => 'btn btn-success btn-sm']) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Url::toRoute(['/admin/modulos', 'cod_modulo'=>$modulo['cod_modulo'] ]), ['class' => 'btn btn-default btn-sm']) ?>
		</div>
	</div>
	<div class="box-body with-border">
		<?= GridView::widget([
			'dataProvider'	 => $dataProvider,
			'filterModel'	 => $searchModel,
			'columns'		 => [
				[
					'attribute'	 => 'txt_nome',
					'content'	 => function($data) {
						return TabPerfisSearch::getTextoPorAtributo('txt_nome', $data->txt_nome);
					},
					'filter' => $filter_nome,
				],
				[
					'attribute'	 => 'dsc_perfil',
					'content'	 => function($data) {
						return TabPerfisSearch::getTextoPorAtributo('dsc_perfil', $data->dsc_perfil);
					},
					'filter'		 => $filter_descricao,
				],
				[
					'class'		 => 'projeto\grid\ActionColumn',
					'template'	 => '{view} {admin} {delete} {usuario} {menu} ',
					'buttons'	 => [
						'admin' => function ($action, $model, $key) {
							return Html::a('<span class="fa fa-edit"></span>', Url::to(['perfis/admin', 'id' => $key, 'cod_modulo' => $model->cod_modulo_fk]), [
								'title'		 => 'Alterar',
								'aria-label' => 'Alterar',
								'data-pjax'	 => '0',
								'data-toggle' => 'tooltip',
							]);
						},
						'usuario' => function ($action, $model, $key) {
							return Html::a('<span class="fa fa-users"> </span>', Url::to(['/admin/usuarios-perfis/admin', 'cod_perfil' => $key]), [
								'title'		 => 'Vincular Usuários ao Perfil',
								'data-pjax'	 => '0',
								'data-toggle' => 'tooltip',
							]);
						},
						'menu' => function ($action, $model, $key) {
							return Html::a('<span class="fa fa-list"> </span>', Url::to(['/admin/menus-perfis/admin', 'cod_perfil' => $key]), [
								'title'		 => 'Vincular Menus ao Perfil',
								'data-pjax'	 => '0',
								'data-toggle' => 'tooltip',
							]);
						},
					],
				],
			],
		]) ?>
	</div>

	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			<?= Html::a('<i class="glyphicon glyphicon-plus"></i> Incluir novo registro', Url::to(['admin', 'cod_modulo' => $modulo['cod_modulo']]), [ 'class' => 'btn btn-success btn-sm']) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Url::toRoute(['/admin/modulos', 'cod_modulo'=>$modulo['cod_modulo'] ]), ['class' => 'btn btn-default btn-sm']) ?>
		</div>
	</div>

</div>
