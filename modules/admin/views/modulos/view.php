<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabModulos */

$infoModulo = $this->context->module->info;

?>


<div class="tab-modulos-view box box-default">

    <div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
			<?= Html::a( '<i class="glyphicon glyphicon-pencil"></i> Editar dados', ['admin', 'id' => $model->cod_modulo], ['class' => 'btn btn-primary btn-sm'] ) ?>
			<?=
			Html::a( '<i class="glyphicon glyphicon-remove"></i> Excluir registro', ['delete', 'id' => $model->cod_modulo], [
				'class'	 => 'btn btn-danger btn-sm',
				'data'	 => [
					'confirm'	 => 'Confirma a exclusão permanente deste registro?',
					'method'	 => 'post',
				],
			] )
			?>
			<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Url::toRoute( $infoModulo['menu-item']['txt_url'] ), ['class' => 'btn btn-default btn-sm'] ) ?>
		</div>
    </div>    
	
	<div class="box-body">
		<?=
		DetailView::widget( [
			'model'		 => $model,
			'attributes' => [
										[
						'attribute'=>'txt_icone',
						'value'=> $model->txt_icone,
						'format' => ['image',['width'=>'100','height'=>'100']],
					],
				'cod_modulo',
				'txt_nome',
				'id',
				'dsc_modulo',
				'txt_url:url',
				'txt_icone',
				'txt_tema',
                'txt_equipe',
                'txt_email_equipe',
                'num_fones_equipe',
                [
                    'label'	 => $model->getAttributeLabel('flag_inicio_equipe'),
                    'value'	 => ($model->flag_inicio_equipe == 'S') ? '[S] Sim' : '[N] Não',
                ],
				'txt_login_inclusao:email',
				'dte_inclusao:date',
				'dte_alteracao:date',
				[
					'label'	 => 'Perfis',
					'value'	 => $model->lista_perfis,
				],

			],
		] )
		?>
	</div>    
	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			<?= Html::a( '<i class="glyphicon glyphicon-pencil"></i> Editar dados', ['admin', 'id' => $model->cod_modulo], ['class' => 'btn btn-primary btn-sm'] ) ?>
			<?=
			Html::a( '<i class="glyphicon glyphicon-remove"></i> Excluir registro', ['delete', 'id' => $model->cod_modulo], [
				'class'	 => 'btn btn-danger btn-sm',
				'data'	 => [
					'confirm'	 => 'Confirma a exclusão permanente deste registro?',
					'method'	 => 'post',
				],
			] )
			?>
			<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Url::toRoute( $infoModulo['menu-item']['txt_url'] ), ['class' => 'btn btn-default btn-sm'] ) ?>
		</div>
    </div>
</div>

