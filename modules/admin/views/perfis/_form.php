<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabPerfis */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tab-perfis-form box box-default">
	<?php $form = ActiveForm::begin(); ?>
    <div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
			<?= Html::submitButton( '<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')] ) ?>
			<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Cancelar', Url::to( ['perfis/index', 'cod_modulo' => $modulo['cod_modulo']] ), ['class' => 'btn btn-default btn-sm'] ) ?>
		</div>
    </div>
	<div class="box-body">
		<?= $form->field( $model, 'txt_nome' )->textInput() ?>
		<?= $form->field( $model, 'dsc_perfil' )->textInput() ?>
	</div>
	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			<?= Html::submitButton( '<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')] ) ?>
			<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Cancelar', Url::to( ['perfis/index', 'cod_modulo' => $modulo['cod_modulo']] ), ['class' => 'btn btn-default btn-sm'] ) ?>
		</div>
    </div>
	<?php ActiveForm::end(); ?>
</div>