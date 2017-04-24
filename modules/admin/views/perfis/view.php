<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\components\MenuLateralModuloWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabPerfis */

$infoModulo = $this->context->module->info;

?>

<div class="tab-perfis-view box box-default">
    <div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
			<?= Html::a( '<i class="glyphicon glyphicon-pencil"></i> Editar dados', ['admin', 'id' => $model->cod_perfil, 'cod_modulo' => $model->cod_modulo_fk], ['class' => 'btn btn-primary btn-sm'] ) ?>
			<?=
			Html::a( '<i class="glyphicon glyphicon-remove"></i> Excluir registro', ['delete', 'id' => $model->cod_perfil], [
				'class'	 => 'btn btn-danger btn-sm',
				'data'	 => [
					'confirm'	 => 'Confirma a exclusão permanente deste registro?',
					'method'	 => 'post',
				],
			] )
			?>
			<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Url::to( ['index', 'cod_modulo' => $modulo['cod_modulo']] ), ['class' => 'btn btn-default btn-sm'] ) ?>
		</div>  
	</div>
	<div class="box-body">
		<?=
		DetailView::widget( [
			'model'		 => $model,
			'attributes' => [
				'cod_perfil',
				'txt_nome',
				'dsc_perfil',
				'txt_login_inclusao:email',
				'dte_inclusao:date',
				'dte_alteracao:date',
			],
		] )
		?>
	</div>
    <div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
			<?= Html::a( '<i class="glyphicon glyphicon-pencil"></i> Editar dados', ['admin', 'id' => $model->cod_perfil, 'cod_modulo' => $model->cod_modulo_fk], ['class' => 'btn btn-primary btn-sm'] ) ?>
			<?=
			Html::a( '<i class="glyphicon glyphicon-remove"></i> Excluir registro', ['delete', 'id' => $model->cod_perfil], [
				'class'	 => 'btn btn-danger btn-sm',
				'data'	 => [
					'confirm'	 => 'Confirma a exclusão permanente deste registro?',
					'method'	 => 'post',
				],
			] )
			?>
			<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Url::to( ['index', 'cod_modulo' => $modulo['cod_modulo']] ), ['class' => 'btn btn-default btn-sm'] ) ?>
		</div>  
	</div>
</div>
