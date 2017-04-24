<?php
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\TabUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\models\TabAtributosValoresSearch;
use app\models\TabAtributosSearch;
?>


<div class="box box-default">

    <div class="box-header with-border">
        <h3 class="box-title">Consulta</h3>
        <div class="box-tools pull-right">
			<?= Html::a('<i class="glyphicon glyphicon-plus"></i> Incluir novo registro', ['admin'], [ 'class' => 'btn btn-success btn-sm']) ?>
        </div>
    </div>

    <div class="box-body with-border">
		<?=
		GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'columns' => [
				'nome_usuario',
				'txt_login',
				'txt_email:email',
				'num_cpf',
				[
					'attribute' => 'txt_ativo',
					'content' => function($data) {
						return TabAtributosValoresSearch::getDescricaoAtributoValor(TabAtributosSearch::OPT_SIM_NAO, $data->txt_ativo);
					},
					'contentOptions' => ['style' => 'width: 100px'],
					'filter' => TabAtributosValoresSearch::getAtributoValor(TabAtributosSearch::OPT_SIM_NAO),
				],
				[
					'attribute' => 'cod_modulo_fk',
					'content' => function($data) {
						$valor = null;
						if ($data->cod_modulo_fk) {
							$valor = \app\modules\admin\models\TabModulos::findOne(['cod_modulo' => $data->cod_modulo_fk])->txt_nome;
						}
						return ($valor) ? $valor : null;
					},
						'filter' => yii\helpers\ArrayHelper::map(
							\app\modules\admin\models\TabModulos::find()
								->where('dte_exclusao is null')
								->asArray()->all(), 'cod_modulo', 'txt_nome'),
					],
					[
						'attribute' => 'cod_perfil_fk',
						'content' => function($data) {
							$valor = null;
							if ($data->cod_modulo_fk) {
								$valor = \app\modules\admin\models\TabPerfisSearch::findOne(['cod_perfil' => $data->cod_perfil_fk])->txt_nome;
							}
							return ($valor) ? $valor : null;
						},
							'filter' => ($searchModel->cod_modulo_fk) ?
								yii\helpers\ArrayHelper::map(\app\modules\admin\models\TabPerfisSearch::find()
										->where('dte_exclusao is null')->andWhere(['cod_modulo_fk' => $searchModel->cod_modulo_fk])
										->asArray()->all(), 'cod_perfil', 'txt_nome') :
								yii\helpers\ArrayHelper::map(\app\modules\admin\models\TabPerfisSearch::find()
										->where('dte_exclusao is null')
										->asArray()->all(), 'cod_perfil', 'txt_nome')
						],
						[
							'class' => 'projeto\grid\ActionColumn',
							'template' => '{view} {admin} {delete} {restringir}',
							'buttons' => [
								'view' => function ($action, $model, $key) {
									return Html::a('<span class="fa fa-search-plus"> </span>', Url::to(['/admin/usuarios/view', 'id' => $model->cod_usuario_fk]), [
											'title' => 'Exibir',
											'aria-label' => Yii::t('yii', 'View'),
											'data-pjax' => '0',
									]);
								},
									'admin' => function ($action, $model, $key) {
									return Html::a('<span class="fa fa-edit"> </span>', Url::to(['/admin/usuarios/admin', 'id' => $model->cod_usuario_fk]), [
											'title' => 'Alterar',
											'aria-label' => 'Alterar',
											'data-pjax' => '0',
									]);
								},
									'delete' => function ($action, $model, $key) {
									if ($this->context->module->getInfo()['usuario-perfil']['cod_usuario_fk'] != $model->cod_usuario_fk) {
										return Html::a('<span class="fa fa-trash"></span>', Url::to(['usuarios/delete', 'id' => $model->cod_usuario_fk]), [
												'data-confirm' => 'Confirma a exclusão do registro?',
												'data-method' => 'post',
												'data-toggle' => 'tooltip',
												'title' => 'Excluir',
										]);
									}
								},
									'restringir' => function ($action, $model, $key) {
									return Html::a('<span class="glyphicon glyphicon-file"> </span>', Url::to(['/admin/restricoes-usuarios/admin', 'cod_usuario_fk' => $model->cod_usuario_fk]), [
											'title' => 'Restrigir Acesso',
											'data-pjax' => '0',
											'data-toggle' => 'tooltip'
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
						<?= Html::a('<i class="glyphicon glyphicon-plus"></i> Incluir novo registro', ['admin'], [ 'class' => 'btn btn-success btn-sm']) ?>
					</div>
				</div>
			</div>