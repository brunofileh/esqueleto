<?php
/* @var $this yii\web\View */
/* @var $searchModel app\models\TabBlocoInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\components\MenuLateralModuloWidget;

$infoModulo = $this->context->module->info;

?>

<?php  $this->beginBlock('conteudo-principal') ?>
<div class="bloco-info-index box box-default">

	<div class="box-header with-border">
		<h3 class="box-title">Consulta</h3>
		<div class="box-tools pull-right">
			<?= Html::a('<i class="glyphicon glyphicon-plus"></i> Incluir novo registro', ["create?TabBlocoInfoSearch[fk_form]={$this->request->queryParams['TabBlocoInfoSearch']['fk_form']}"], ['class' => 'btn btn-success btn-sm']) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar',  Url::toRoute("/drenagem/cadastro-formularios"), ['class' => 'btn btn-default btn-sm']) ?>
		</div>
	</div>

	<div class="box-body with-border">
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			'num_ordem_bloco',
			'dsc_titulo_bloco',
			'sgl_id',
			['class' => 'projeto\grid\ActionColumn'],
		],
	]); ?>
	</div>
	
	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			<?= Html::a('<i class="glyphicon glyphicon-plus"></i> Incluir novo registro', ["create?TabBlocoInfoSearch[fk_form]={$this->request->queryParams['TabBlocoInfoSearch']['fk_form']}"], ['class' => 'btn btn-success btn-sm']) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar',  Url::toRoute("/drenagem/cadastro-formularios"), ['class' => 'btn btn-default btn-sm']) ?>
		</div>
	</div>
</div>


<?php  $this->endBlock() ?>
