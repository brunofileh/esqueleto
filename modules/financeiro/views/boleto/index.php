<?php
/* @var $this yii\web\View */
/* @var $searchModel app\modules\financeiro\models\TabBoletoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\components\MenuLateralModuloWidget;

$infoModulo = $this->context->module->info;

?>

<div class="boleto-index box box-default">

	<div class="box-header with-border">
		<h3 class="box-title">Consulta</h3>
		<div class="box-tools pull-right">
			 <?= Html::a('<i class="glyphicon glyphicon-plus"></i> Incluir novo registro', ['admin'], ['class' => 'btn btn-success btn-sm']) ?>
		</div>
	</div>

	<div class="box-body with-border">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'nu_documento',
            'dt_vencimento',
            'ds_valor',
            'cod_tipo_contrato_fk',
            'nu_doc',
            'dt_inclusao',
            'nosso_numero',
            'valor_multa',
            'multa',
            // 'valor_juros',
            // 'dt_pagamento',
            // 'fic_comp',
            // 'fic_comp2',
            // 'advogado:boolean',
            // 'valor_pago',
            // 'ativo:boolean',
            // 'dt_processamento',
            // 'dt_ocorrencia',
            // 'dt_exclusao',
            // 'justificativa_exclusao:ntext',
            ['class' => 'projeto\grid\ActionColumn'],
        ],
    ]); ?>
	</div>
	
	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			 <?= Html::a('<i class="glyphicon glyphicon-plus"></i> Incluir novo registro', ['admin'], ['class' => 'btn btn-success btn-sm']) ?>
		</div>
	</div>
</div>


