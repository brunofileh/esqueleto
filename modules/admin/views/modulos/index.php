<?php
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\TabModulosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\modules\admin\models\TabModulosSearch;
?>


<div class="box box-default">

	<div class="box-header with-border">
		<h3 class="box-title">Consulta</h3>
		<div class="box-tools pull-right">
			<?= Html::a( '<i class="glyphicon glyphicon-plus"></i> Incluir novo registro', ['admin'], [ 'class' => 'btn btn-success btn-sm'] ) ?>
			<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Voltar' , Url::toRoute( '/admin/inicio' ) , ['class' => 'btn btn-default btn-sm'] ) ?>
		</div>
	</div>

	<div class="box-body with-border">
		<?= GridView::widget( [
			'dataProvider'	 => $dataProvider,
			'filterModel'	 => $searchModel,
			'columns'		 => [
				[
					'attribute'	 => 'id' ,
					'content'	 => function($data){
						return TabModulosSearch::getTextoPorAtributo('id', $data->id);
					} ,
					'contentOptions' => ['style' => 'width: 200px'] ,
					'filter'		 => TabModulosSearch::getListaPorAtributo('id'),
				] ,
				[
					'attribute'	 => 'txt_nome' ,
					'content'	 => function($data){
						return TabModulosSearch::getTextoPorAtributo('txt_nome', $data->txt_nome);
					} ,
					'contentOptions' => ['style' => 'width: 200px'] ,
					'filter'		 => TabModulosSearch::getListaPorAtributo('txt_nome'),
				] ,

				'dsc_modulo',
				[
					'attribute' => 'txt_url',
					'content' => function ($model) {
						return Html::a($model->txt_url, Url::toRoute($model->txt_url));
					}
				],
				[
					'attribute' => 'txt_icone',
					'content' => function ($model) {
						return Html::img($model->txt_icone, [
							'width' => '30',
							'height' => '30',
						]);
					},
					'contentOptions' => ['align' => 'center']
				],
				[	
					'class'		 => 'projeto\grid\ActionColumn',
					'template'	 => '{view} {admin} {delete} {perfil} {menu} {funcionalidade}',
					'buttons'	 => [
						'perfil' => function ($urls, $key, $class) {
							return Html::a('<span class="fa  fa-file-o"> </span>', Url::to( ['/admin/perfis/index', 'cod_modulo' => $class] ), [
								'title'		 => 'Perfil',
								'data-pjax'	 => '0',
								'data-toggle' => 'tooltip',
							]);
						},
						'menu' => function ($url, $key, $class) {
							return Html::a('<span class="fa fa-list-ul"> </span>', Url::to( ['/admin/menus/index', 'cod_modulo' => $class] ), [
								'title'		 => 'Menu',
								'data-pjax'	 => '0',
								'data-toggle' => 'tooltip',
							]);
						},
						'funcionalidade' => function ($url, $key, $class) {
							return Html::a('<span class="fa fa-th"> </span>', Url::to( ['/admin/funcionalidades/index', 'cod_modulo' => $class] ), [
								'title'		 => 'Funcionalidade',
								'data-pjax'	 => '0',
								'data-toggle' => 'tooltip',
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
				<?= Html::a( '<i class="glyphicon glyphicon-plus"></i> Incluir novo registro' , ['admin'] , [ 'class' => 'btn btn-success btn-sm'] ) ?>
				<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Voltar' , Url::toRoute( '/admin/inicio' ) , ['class' => 'btn btn-default btn-sm'] ) ?>
			</div>
		</div>
	</div>
