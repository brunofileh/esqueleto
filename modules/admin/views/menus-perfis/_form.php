<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\RlcMenusPerfis */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="rlc-menu-perfis-form box box-default">
	<?php $form = ActiveForm::begin(); ?>
    <div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
			<?= Html::submitButton( '<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')] ) ?>
			<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Cancelar', Url::to( ['perfis/index', 'cod_modulo' => $model->tabPerfis->cod_modulo_fk] ), ['class' => 'btn btn-default btn-sm'] ) ?>
		</div>
    </div>
	<div class="box-body">
		<div id="dualListBoxMenuPerfil">
			<?=
			$form->field( $model, 'lista_menus' )->widget( 'maksyutin\duallistbox\Widget', [
				'model'		 => $model,
				'attribute'	 => 'lista_menus',
				'title'		 => 'Menus',
				'data'		 => $listaMenus,
				'data_id'	 => 'cod_menu_fk',
				'data_value' => 'nome_menu',
				'lngOptions' => array (
					'search_placeholder' => 'Buscar Menus',
					'showing'			 => 'Exibindo',
					'available'			 => 'Menus cadastrados',
					'selected'			 => 'Menus selecionados'
				)
			] );
			?>
		</div>
	</div>
	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			<?= Html::submitButton( '<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')] ) ?>
			<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Cancelar', Url::to( ['perfis/index', 'cod_modulo' => $model->tabPerfis->cod_modulo_fk] ), ['class' => 'btn btn-default btn-sm'] ) ?>
		</div>
    </div>
	<?php ActiveForm::end(); ?>
</div>