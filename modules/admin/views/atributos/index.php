<?php
/* @var $this yii\web\View */
/* @var $searchModel app\models\TabAtributosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use projeto\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\components\MenuLateralModuloWidget;
use app\models\TabAtributosValores;
use yii\widgets\Pjax;

$infoModulo = $this->context->module->info;

?>


<div class="atributos-index box box-default">

	<div class="box-header with-border">
		<h3 class="box-title">Consulta</h3>
		<div class="box-tools pull-right">
			 <?= Html::a('<i class="glyphicon glyphicon-plus"></i> Incluir novo registro', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
		</div>
	</div>

	<div class="box-body with-border">
		<?php Pjax::begin() ?>
			<?= GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'columns' => [
					'cod_atributos',
					'dsc_descricao',
					'sgl_chave',
					[
						'class' => 'yii\grid\DataColumn',
						'label' => 'Itens',
						'value' => function ($model, $key) {
							return TabAtributosValores::find()
								->where(['fk_atributos_valores_atributos_id' => $key])
								->count();
						}
					],
					[
						'class' => 'projeto\grid\ActionColumn',
						'header' => 'Ações',
						'template' => '{view} {update} {delete} {menu}',
						'buttons' => [
							'view' => function ($urls, $model, $class) use ($infoModulo) {
								$items = '';
								$r = TabAtributosValores::find()
									->where(['fk_atributos_valores_atributos_id' => $class])
									->asArray()
									->all();

								foreach ($r as $item) {
									$items .= "{$item['dsc_descricao']}<br>";
								}

								return Html::a(Html::ficon('eye'), Url::to(['view', 'id' => $class]), [
									'title' => "<p class=\"text-left\">{$items}</p>",
									'data-pjax' => '0',
									'data-toggle' => 'tooltip',
									'data-placement' => 'left',
									'data-html' => 'true',
								]);
							},
							'menu' => function ($urls, $model, $class) use ($infoModulo) {
								 $options = [
									'title' => 'Gerenciar itens',
									'aria-label' => 'Gerenciar itens',
									'data-pjax' => '0',
								 	'data-toggle' => 'tooltip',
									'data-placement' => 'top',
								];

								return Html::a('<span class="fa fa-list-ol"> </span>', Url::to([
									"{$infoModulo['txt_url']}/atributos-valores", 
									'TabAtributosValoresSearch[fk_atributos_valores_atributos_id]' => $class
								]), $options);
							}
						],
					],
				],
			]); ?>
		<?php Pjax::end() ?>
	</div>
	
	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			 <?= Html::a('<i class="glyphicon glyphicon-plus"></i> Incluir novo registro', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
		</div>
	</div>
</div>

