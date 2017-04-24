<?php
/* @var $this yii\web\View */
/* @var $searchModel app\models\TabParametrosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use app\models\TabParametros;
use app\modules\admin\models\TabModulos;

$infoModulo = $this->context->module->info;
$txtPrompt = ['' => $this->app->params['txt-prompt-select']];
$filterAno = $txtPrompt +  ArrayHelper::map(TabParametros::find()->select('num_ano_ref')->distinct('num_ano_ref')->asArray()->all(), 'num_ano_ref', 'num_ano_ref');
$filterModulo =  $txtPrompt + ArrayHelper::map(TabModulos::find()->select(['cod_modulo', 'txt_nome'])->asArray()->all(), 'cod_modulo', 'txt_nome');

?>

<?php  $this->beginBlock('conteudo-principal') ?>
<div class="parametros-index box box-default">

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
				[
					'attribute' => 'modulo_fk',
					'filter' => Html::dropDownList(
						'TabParametrosSearch[modulo_fk]', 
						isset($this->session->get('parametros.filtro')['TabParametrosSearch']['modulo_fk']) 
							? $this->session->get('parametros.filtro')['TabParametrosSearch']['modulo_fk'] 
							: null, 
						$filterModulo, 
						['class' => 'form-control']
					),
					'content' => function ($model) {
						if ($model->modulo_fk !== null) {
							return TabModulos::find()->where(['cod_modulo' => $model->modulo_fk])->select(['txt_nome'])->asArray()->one()['txt_nome'];
						}
						else {
							return '';
						}
					},
				],
				[
					'attribute' => 'num_ano_ref',
					'filter' => Html::dropDownList(
						'TabParametrosSearch[num_ano_ref]', 
						isset($this->session->get('parametros.filtro')['TabParametrosSearch']['num_ano_ref']) 
							? $this->session->get('parametros.filtro')['TabParametrosSearch']['num_ano_ref'] 
							: null, 
						$filterAno, 
						['class' => 'form-control']
					),
					'headerOptions' => ['style' => ['width' => '150px']],
				],
				'sgl_parametro',
				'vlr_parametro',
				'dsc_parametro',
				[
					'class' => 'projeto\grid\ActionColumn',
					'headerOptions' => ['style' => ['width' => '70px']],
				],
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


<?php  $this->endBlock() ?>
