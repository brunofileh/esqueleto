<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\TabAtributosValoresSearch;
use app\models\TabAtributosSearch;
use \cyneek\yii2\widget\upload\crop\UploadCrop;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabUsuarios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tab-modulos-form box box-default">

	<?php $form = ActiveForm::begin( ['options' => ['enctype' => 'multipart/form-data']] ); ?>

    <div class="box-header with-border">
        <h3 class="box-title">&nbsp;</h3>
        <div class="box-tools">
			<?= Html::submitButton( '<i class="glyphicon glyphicon-ok"></i> Alterar registro' , ['class' => 'btn btn-primary btn-sm'] ) ?>
			<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Cancelar' , Url::to(['view']) , ['class' => 'btn btn-default btn-sm'] ) ?>
        </div>

    </div>

    <div class="box-body">
		<div>
			<?=
			Html::img( $model->txt_imagem , [
				'class'	 => 'img-circle' ,
				'width'	 => '80px' ,
				'height' => '80px' ,
			] )
			?>
		</div>
		<br />
		<div class="form-group field-tabusuariossearch-num_cpf">

			<label for="tabusuariossearch-num_cpf" class="control-label"><?= $model->getAttributeLabel( 'num_cpf' ) ?></label>
			<div><?= $model->num_cpf ?>
			</div>

		</div>

		<div class="form-group field-tabusuariossearch-txt_login">

			<label for="tabusuariossearch-txt_login" class="control-label"><?= $model->getAttributeLabel( 'txt_login' ) ?></label>
			<div><?= $model->txt_login ?>
			</div>

		</div>

		<?= $form->field( $model , 'txt_nome' )->textInput( ['maxlength' => true] ) ?>

		<?= $form->field( $model , 'txt_email' )->textInput( ['maxlength' => true] ) ?>
		
		<?=
		$form->field( $model , 'num_fone' )->widget( MaskedInput::className() , [
			'mask' => ['(99) 9999-9999' , '(99) 99999-9999'] ,
		] );
		?>		

		<?= $form->field( $model , 'txt_imagem_cropping' )->widget( UploadCrop::className() )->label( false )->error( false ); ?>  </div>

    <div class="box-footer">
        <div class="box-tools pull-right">
			<?= Html::submitButton( '<i class="glyphicon glyphicon-ok"></i> Alterar registro' , ['class' => 'btn btn-primary btn-sm'] ) ?>
			<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Cancelar' , Url::toRoute(['view']) , ['class' => 'btn btn-default btn-sm'] ) ?>
		</div>
    </div>
	<?php ActiveForm::end(); ?>
</div>