<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \cyneek\yii2\widget\upload\crop\UploadCrop;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabModulos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tab-modulos-form box box-default">

	<?php $form = ActiveForm::begin( ['options' => ['enctype' => 'multipart/form-data']] ); ?>


    <div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
			<?= Html::submitButton( '<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro') , ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')] ) ?>
			<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Cancelar' , Url::toRoute( $infoModulo['menu-item']['txt_url'] ) , ['class' => 'btn btn-default btn-sm'] ) ?>
		</div>

    </div>
	<div class="box-body">

		<div>
			<?=
			Html::img( $model->txt_icone , [
				'class'	 => 'img-circle' ,
				'width'	 => '80px' ,
				'height' => '80px' ,
			] )
			?>
		</div>

		<?= $form->field( $model , 'txt_nome' )->textInput() ?>
		<?= $form->field( $model , 'id' )->textInput() ?>
		<?= $form->field( $model , 'dsc_modulo' )->textInput() ?>
		<?= $form->field( $model , 'txt_url' )->textInput() ?>
		<?= $form->field( $model , 'txt_icone_cropping' )->widget(UploadCrop::className(), ['form' => $form])->label( false ); ?>  

		<div class="row">
			<div class="col-lg-3">
				<ul class="list-unstyled clearfix">
					<?= $form->field( $model , 'txt_tema' )->radioList( $model->lista_temas , [
						'item' => function($index , $label , $name , $checked , $value){
							if (strrpos( $value , 'light' ) === false){
								$bgNav = '#222d32';
							} else{
								$bgNav = '#f4f5f7';
							}

							if ($checked){
								$checked = 'checked';
							}
							$return = '<li style="float:left; width: 33.33333%; padding: 5px;">

								<div>
									<span style="display:block; width: 20%; float: left; height: 7px;" class="bg-' . strtolower( $label ) . '"></span>
									<span style="display:block; width: 80%; float: left; height: 7px;" class="bg-' . strtolower( $label ) . '"></span>
								</div>
								<div>
									<span style="display:block; width: 20%; float: left; height: 20px; background: ' . $bgNav . '"></span>
									<span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span>
								</div>

							<p class="text-center no-margin"><input type="radio" name="' . $name . '" value="' . $value . '" ' . $checked . '>	' . $label . '</p>
							
						</li>';
							return $return;
						}
						]
					) ?>
				</ul>
			</div>
		</div>
		<?= $form->field( $model , 'txt_equipe' )->textInput() ?>
        <?= $form->field( $model , 'txt_email_equipe' )->textInput() ?>
        <?= $form->field( $model , 'num_fones_equipe' )->textInput() ?>
        <?= $form->field($model, 'flag_inicio_equipe')->dropDownList(
            \app\models\TabAtributosValoresSearch::getAtributoValor(\app\models\TabAtributosSearch::OPT_SIM_NAO), ['prompt' => $this->app->params['txt-prompt-select']]
        ) ?>
	</div>
</div>

<div class="box-footer">
	<h3 class="box-title"></h3>
	<div class="box-tools pull-right">
		<?= Html::submitButton( '<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro') , ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')] ) ?>
		<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Cancelar' , Url::toRoute( $infoModulo['menu-item']['txt_url'] ) , ['class' => 'btn btn-default btn-sm'] ) ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>
