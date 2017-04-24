<?php
/* @var $this yii\web\View */
/* @var $searchModel app\models\TabAtributosValoresSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use projeto\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\components\MenuLateralModuloWidget;

$infoModulo = $this->context->module->info;

if (isset(Yii::$app->request->queryParams['TabAtributosValoresSearch']['fk_atributos_valores_atributos_id'])) {
	$fk_atributos_valores_atributos_id = Yii::$app->request->queryParams['TabAtributosValoresSearch']['fk_atributos_valores_atributos_id'];
}
else {
	throw new yii\web\NotFoundHttpException('Página não encontrada');
}

?>

<?php  $this->beginBlock('conteudo-principal') ?>
<div class="atributos-valores-index box box-default">

	<div class="box-header with-border">
		<h3 class="box-title">Consulta</h3>
		<?php  $this->beginBlock('menu-acoes') ?>
			<div class="box-tools pull-right">
				 <?= Html::a(Html::icon('plus', 'Incluir novo registro'), ['create', 'TabAtributosValoresSearch[fk_atributos_valores_atributos_id]' => $fk_atributos_valores_atributos_id], ['class' => 'btn btn-success btn-sm']) ?>
				 <?= Html::a(Html::icon('arrow-left', 'Voltar'),  Url::toRoute("{$infoModulo['txt_url']}/atributos"), ['class' => 'btn btn-default btn-sm']) ?>
			</div>
		<?php  $this->endBlock() ?>
		<?= $this->blocks['menu-acoes'] ?>
	</div>

	<div class="box-body with-border">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'cod_atributos_valores',
            [
            	'attribute' => 'fk_atributos_valores_atributos_id',
            	'filter' => false,
            ],
            'sgl_valor',
            'dsc_descricao',
            ['class' => 'projeto\grid\ActionColumn'],
        ],
    ]); ?>
	</div>
	
	<div class="box-footer">
		<h3 class="box-title"></h3>
		<?= $this->blocks['menu-acoes'] ?>
	</div>
</div>


<?php  $this->endBlock() ?>
