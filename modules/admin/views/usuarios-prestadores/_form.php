<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\tabs\TabsX;
use yii\widgets\MaskedInput;
use app\models\TabAtributosValoresSearch;
use app\models\TabAtributosSearch;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabUsuarios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tab-usuarios-form box box-default">
    <?php $form = ActiveForm::begin(); ?>

    <div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
			<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> '. ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')]) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Cancelar',  Url::toRoute($infoModulo['menu-item']['txt_url']), ['class' => 'btn btn-default btn-sm']) ?>
		</div>
    </div>
	
	<div class="box-body">
    <?= $form->field($model, 'cod_prestador_fk')->dropDownList(
		ArrayHelper::map(
			$listaPrestadores,
			'cod_prestador', 
			'txt_nome'
		),
	['prompt' => $this->app->params['txt-prompt-select']]); ?>
    <?= $form->field($model, 'txt_nome')->textInput(['maxlength' => true]) ?>		
	<?= $form->field( $model , 'txt_login' )->textInput( ['maxlength' => true] ) ?>

	<?=
	$form->field( $model , 'num_cpf' )->widget( MaskedInput::className() , [
		'mask' => ['999.999.999-99'] ,
	] );
	?>
	<?=
	$form->field($model, 'num_fone')->widget(MaskedInput::className(), [
		'mask' => ['(99) 9999-9999', '(99) 99999-9999'],
	]);
	?>				
    <?= $form->field($model, 'txt_email')->textInput(['maxlength' => true]) ?>
    
	<?php			
		echo $form->field($model, 'txt_ativo')->dropDownList(
			TabAtributosValoresSearch::getAtributoValor(TabAtributosSearch::OPT_SIM_NAO), ['prompt' => $this->app->params['txt-prompt-select']]
		);
			
		$active = true;
		foreach ($arrModulos as $key => $value) {
			$itens[] = 
				[
					'label'   => $value['nome_modulo'],
					'content' => $this->render('_usuarios_modulos', [
						'model' => $model, 
						'cod_modulo' => $value['cod_modulo'],						
						'arrPerfis' => $arrPerfis[$value['cod_modulo']],
						'arrFuncionalidades' => $arrFuncionalidades[$value['cod_modulo']],								
					]),
					'active'  => $active
				]
			;
			$active = false;
		}
		
		echo TabsX::widget([
			'items'        => $itens,
			'position'     => TabsX::POS_ABOVE,
			'bordered'     => true,
			'encodeLabels' => false
		]);
	?>		
    </div>

	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> '. ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')]) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Cancelar',  Url::toRoute($infoModulo['menu-item']['txt_url']), ['class' => 'btn btn-default btn-sm']) ?>
		</div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
