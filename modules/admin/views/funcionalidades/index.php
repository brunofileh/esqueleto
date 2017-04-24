<?php
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\TabFuncionalidadesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\modules\admin\models\TabFuncionalidadesSearch;

$infoModulo = $this->context->module->info;

$filter_nome		 = TabFuncionalidadesSearch::getListaPorAtributo('txt_nome', $modulo['cod_modulo']);
$filter_descricao	 = TabFuncionalidadesSearch::getListaPorAtributo('dsc_funcionalidade', $modulo['cod_modulo']);
?>

<div class="funcionalidades-index box box-default">
	<div class="box-header with-border">
		<h3 class="box-title">Consulta</h3>
		<div class="box-tools pull-right">
			<?= Html::a('<i class="glyphicon glyphicon-plus"></i> Incluir novo registro', ['admin', 'cod_modulo' => $modulo['cod_modulo']], ['class' => 'btn btn-success btn-sm']) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Url::toRoute(['/admin/modulos', 'cod_modulo' => $modulo['cod_modulo']]), ['class' => 'btn btn-default btn-sm']) ?>
		</div>
	</div>

	<div class="box-body with-border">
		<?=
		GridView::widget([
			'dataProvider'	 => $dataProvider,
			'filterModel'	 => $searchModel,
			'columns'		 => [
				'txt_nome',
				'dsc_funcionalidade',
				
				[
					'class'		 => 'projeto\grid\ActionColumn',
					'template'	 => '{view} {admin} {delete} {vincular}',
					'buttons'	 => [
						'view' => function ($action, $model, $key) {
			
							return Html::a('<span class="fa fa-eye"></span>', Url::to(['funcionalidades/view', 'id' => $model->cod_funcionalidade, 'cod_modulo' => ( ($model->rlcPerfisFuncionalidadesAcoes) ?$model->rlcPerfisFuncionalidadesAcoes[0]->tabPerfis->cod_modulo_fk : $model->app->controller->actionParams['cod_modulo']) ]), [
									'data-toggle'	 => 'tooltip',
									'title'			 => 'Exibir',
							]);
						},
							'admin'		 => function ($action, $model, $key) {
							return Html::a('<span class="fa fa-edit"></span>', Url::to(['funcionalidades/admin', 'id' => $model->cod_funcionalidade, 'cod_modulo' => ( ($model->rlcPerfisFuncionalidadesAcoes) ?$model->rlcPerfisFuncionalidadesAcoes[0]->tabPerfis->cod_modulo_fk : $model->app->controller->actionParams['cod_modulo']) ]), [
									'data-toggle'	 => 'tooltip',
									'title'			 => 'Alterar',
							]);
						},
							'delete' => function($action, $model, $key) {
							return Html::a('<span class="fa fa-trash"></span>', Url::to(['funcionalidades/delete', 'id' => $model->cod_funcionalidade, 'cod_modulo' => ( ($model->rlcPerfisFuncionalidadesAcoes) ?$model->rlcPerfisFuncionalidadesAcoes[0]->tabPerfis->cod_modulo_fk : $model->app->controller->actionParams['cod_modulo']) ]), [
									'data-confirm'	 => 'Confirma a exclusão permanente deste registro?',
									'data-method'	 => 'post',
									'data-toggle'	 => 'tooltip',
									'title'			 => 'Excluir',
							]);
						},
							'vincular' => function ($action, $model, $key) {
							return Html::a('<span class="fa fa-clone"></span>', Url::to(['perfis-funcionalidades-acoes/admin', 'cod_funcionalidade' => $model->cod_funcionalidade, 'cod_modulo' => ( ($model->rlcPerfisFuncionalidadesAcoes) ?$model->rlcPerfisFuncionalidadesAcoes[0]->tabPerfis->cod_modulo_fk : $model->app->controller->actionParams['cod_modulo']) ]), [
									'data-toggle'	 => 'tooltip',
									'title'			 => 'Vincular Acões a Funcionalidade',
							]);
						},
						],
					],
				],
			])
			?>
		</div>
		<div class="box-footer">
			<h3 class="box-title"></h3>
			<div class="box-tools pull-right">
	<?= Html::a('<i class="glyphicon glyphicon-plus"></i> Incluir novo registro', ['admin', 'cod_modulo' => $modulo['cod_modulo']], ['class' => 'btn btn-success btn-sm']) ?>
				<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Url::toRoute(['/admin/modulos', 'cod_modulo' => $modulo['cod_modulo']]), ['class' => 'btn btn-default btn-sm']) ?>
			</div>
		</div>
	</div>
