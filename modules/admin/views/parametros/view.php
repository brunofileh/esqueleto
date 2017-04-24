<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TabParametros */

$infoModulo = $this->context->module->info;

?>

<div class="tab-parametros-view box box-default">

    <div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
        <?= Html::a('<i class="glyphicon glyphicon-pencil"></i> Editar dados', ['update', 'id' => $model->cod_parametro], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<i class="glyphicon glyphicon-remove"></i> Excluir registro', ['delete', 'id' => $model->cod_parametro], [
            'class' => 'btn btn-danger btn-sm',
            'data' => [
                'confirm' => 'Confirma a exclusão permanente deste registro?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Url::toRoute($infoModulo['menu-item']['txt_url']), ['class' => 'btn btn-default btn-sm']) ?>
    	</div>
    </div>    
	
	<div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'cod_parametro',
            'modulo_fk',
            'num_ano_ref',
            'sgl_parametro',
            'vlr_parametro',
            'dsc_parametro:ntext',
            'dte_inclusao:date',
            'dte_alteracao:date',
            'dte_exclusao:date',
            'txt_login_inclusao',
        ],
    ]) ?>
	</div>    
	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
		<?= Html::a('<i class="glyphicon glyphicon-pencil"></i> Editar dados', ['update', 'id' => $model->cod_parametro], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<i class="glyphicon glyphicon-remove"></i> Excluir registro', ['delete', 'id' => $model->cod_parametro], [
            'class' => 'btn btn-danger btn-sm',
            'data' => [
                'confirm' => 'Confirma a exclusão permanente deste registro?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Url::toRoute($infoModulo['menu-item']['txt_url']), ['class' => 'btn btn-default btn-sm']) ?>
		</div>
    </div>
</div>
