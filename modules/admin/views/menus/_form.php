<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

//use maksyutin\duallistbox\Widget;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabMenus */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tab-menus-form box box-default">
	<?php $form = ActiveForm::begin(); ?>
    <div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
			<?= Html::submitButton( '<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')] ) ?>
			<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Cancelar', Url::to( ['menus/index', 'cod_modulo' => $modulo['cod_modulo']] ), ['class' => 'btn btn-default btn-sm'] ) ?>
		</div>
    </div>
	<div class="box-body">
		<?= $form->field( $model, 'txt_nome' )->textInput() ?>
		<?= $form->field( $model, 'dsc_menu' )->textInput() ?>
		<?= $form->field( $model, 'txt_url' )->textInput() ?>
		<?= $form->field( $model, 'txt_imagem' )->textInput() ?>
		<?= $form->field( $model, 'num_ordem' )->textInput() ?>
		<?= $form->field( $model, 'num_nivel' )->textInput() ?>

		<?=
		$form->field( $model, 'cod_menu_fk' )->dropDownList(
			ArrayHelper::map( $listaMenusPai, 'cod_menu_fk', 'dsc_menu'
			), ['prompt' => $this->app->params['txt-prompt-select'],
			'class'	 => 'chosen-select'] );
		?>
		<br />
		<?=
		$form->field( $model, 'lista_perfil' )->widget( 'maksyutin\duallistbox\Widget', [
			'model'		 => $model,
			'attribute'	 => 'lista_perfil',
			'title'		 => 'Perfil',
			'data'		 => $listaPerfis,
			'data_id'	 => 'cod_perfil',
			'data_value' => 'txt_nome',
			'lngOptions' => array (
				'search_placeholder' => 'Perfil',
				'showing'			 => 'Exibindo',
				'available'			 => 'Perfis cadastrados',
				'selected'			 => 'Perfis selecionados'
			)
		] );
		?>
	</div>
	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			<?= Html::submitButton( '<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')] ) ?>
			<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Cancelar', Url::to( ['menus/index', 'cod_modulo' => $modulo['cod_modulo']] ), ['class' => 'btn btn-default btn-sm'] ) ?>
		</div>
    </div>
	<?php ActiveForm::end(); ?>
</div>
