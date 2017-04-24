<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\RlcUsuariosPerfis */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rlc-usuarios-perfis-form box box-default">
	<?php $form = ActiveForm::begin(); ?>
    <div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
			<?= Html::submitButton( '<i class="glyphicon glyphicon-ok"></i> ' . ('Alterar registro'), ['class' => ('btn btn-primary btn-sm')] ) ?>
			<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Cancelar', Url::to( ['perfis/index', 'cod_modulo' => $model->tabPerfis->cod_modulo_fk] ), ['class' => 'btn btn-default btn-sm'] ) ?>
		</div>
    </div>
	<div class="box-body">
		<div id="dualListBoxUsuarioPerfil">
			<?=
			maksyutin\duallistbox\Widget::widget( [
				'model'		 => $model,
				'attribute'	 => 'lista_usuarios',
				'title'		 => 'Usuários',
				'data'		 => $listaUsuarios,
				'data_id'	 => 'cod_usuario_fk',
				'data_value' => 'txt_login',
				'lngOptions' => array (
					'search_placeholder' => 'Buscar usuários',
					'showing'			 => 'Exibindo',
					'available'			 => 'Usuários cadastrados',
					'selected'			 => 'Usuários selecionados'
				)
			] );
			?>
		</div>
	</div>
	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
		<?= Html::submitButton( '<i class="glyphicon glyphicon-ok"></i> ' . ('Alterar registro'), ['class' => ('btn btn-primary btn-sm')] ) ?>
			<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Cancelar', Url::to( ['perfis/index', 'cod_modulo' => $model->tabPerfis->cod_modulo_fk] ), ['class' => 'btn btn-default btn-sm'] ) ?>
		</div>
    </div>
	<?php ActiveForm::end(); ?>
</div>