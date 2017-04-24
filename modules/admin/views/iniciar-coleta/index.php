<?php
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\TabAcoesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\modules\admin\models\TabAcoesSearch;

$infoModulo = $this->context->module->info;

$filter_nome      = TabAcoesSearch::getListaPorAtributo('txt_nome');
$filter_descricao = TabAcoesSearch::getListaPorAtributo('dsc_acao');
?>

	<?php $this->beginBlock( 'conteudo-principal' ) ?>
<div class="acoes-index box box-default">

	<div class="box-header with-border">
		<h3 class="box-title">Consulta</h3>
		<div class="box-tools pull-right">
			<?= Html::a( '<i class="glyphicon glyphicon-plus"></i> Incluir novo registro' , ['create'] , ['class' => 'btn btn-success btn-sm'] ) ?>
		</div>
	</div>

	<div class="box-body with-border">
		<?=
		GridView::widget( [
			'dataProvider'	 => $dataProvider ,
			'filterModel'	 => $searchModel ,
			'columns'		 => [
                [
                    'attribute' => 'txt_nome',
                    'content'   => function($data)
                    {
                        return TabAcoesSearch::getTextoPorAtributo('txt_nome', $data->txt_nome);
                    },
                    'filter' => $filter_nome,
                ],
                [
                    'attribute' => 'dsc_acao',
                    'content'   => function($data)
                    {
                        return TabAcoesSearch::getTextoPorAtributo('dsc_acao', $data->dsc_acao);
                    },
                    'filter'       => $filter_descricao,
                ],
//				'txt_nome' ,
//				'dsc_acao' ,
				['class' => 'projeto\grid\ActionColumn'] ,
			] ,
		] );
		?>
	</div>
	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			<?= Html::a( '<i class="glyphicon glyphicon-plus"></i> Incluir novo registro' , ['create'] , ['class' => 'btn btn-success btn-sm'] ) ?>
		</div>
	</div>
</div>