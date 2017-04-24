<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\components\MenuLateralModuloWidget;
use kartik\tabs\TabsX;
use yii\widgets\MaskedInput;
use yii\helpers\ArrayHelper;
use app\models\TabEstadosSearch;
use app\models\TabMunicipiosSearch;

/* @var $this yii\web\View */
/* @var $model app\models\RlcModulosPrestadores */
?>

<div class="row">
	<div class="col-md-12">
		<?=
		$form->field($model, "[" . $model->tabModulos->id . "]txt_secretaria_nome")->textInput(
			['name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][txt_secretaria_nome]', 'maxlength' => true])
		?>
	</div>

</div>

<div class="row">

	<div class="col-md-3">
		<?=
		$form->field($model, '[' . $model->tabModulos->id . ']num_secretaria_cep')->widget(MaskedInput::className(), [
			'mask' => '99999-999',
		])->textInput(['placeholder' => "99.999-999", 'name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][num_secretaria_cep]', 'maxlength' => true])
		?>
	</div>	
	<div class="col-md-5">
		<?= $form->field($model, '[' . $model->tabModulos->id . ']txt_secretaria_endereco')->textInput(['name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][txt_secretaria_endereco]', 'maxlength' => true]) ?>

	</div>

	<div class="col-md-4">
		<?= $form->field($model, '[' . $model->tabModulos->id . ']txt_secretaria_complemento')->textInput(['name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][txt_secretaria_complemento]', 'maxlength' => true]) ?>

	</div>
</div>
<div class="row">



	<div class="col-md-3">
		<?= $form->field($model, '[' . $model->tabModulos->id . ']num_secretaria_numero')->textInput(['name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][num_secretaria_numero]', 'maxlength' => true]) ?>
	</div>

	<div class="col-md-5">
		<?= $form->field($model, '[' . $model->tabModulos->id . ']txt_secretaria_bairro')->textInput(['name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][txt_secretaria_bairro]', 'maxlength' => true]) ?>
	</div>
<div class="col-md-4">
		<?= $form->field($model, '[' . $model->tabModulos->id . ']txt_secretaria_site')->textInput(['name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][txt_secretaria_site]', 'maxlength' => true]) ?>
	</div>
</div>

<?php if (in_array($this->context->module->id, ['admin', 'gestao'])) : ?>
	<div class="row">
		<div class="col-md-6">
			<?=
			$form->field($model, '[' . $model->tabModulos->id . ']sgl_estado_fk')->dropDownList(
				ArrayHelper::map(TabEstadosSearch::find()->orderBy('txt_nome')->all(), 'sgl_estado', 'sgl_estado'
				), ['name'		 => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][sgl_estado_fk]',
				'prompt'	 => '-- selecione --',
				'onchange'	 => "
								   $.post( '" . Yii::$app->urlManager->createUrl("municipios/lista?uf=") . "'+$(this).val(), function( data ) {
									 $( \"select[id*='{$model->tabModulos->id}-cod_municipio_fk']\" ).html( data );

								   });"
			]);
			?>

		</div>

		<div class="col-md-6">
			<?=
			$form->field($model, '[' . $model->tabModulos->id . ']cod_municipio_fk')->dropDownList($model->listaMunicipios, ['prompt'	 => '-- selecione --',
				// 'id'		 => 'municipio',
				'class'		 => 'form-control',
				'name'		 => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][cod_municipio_fk]',
				'maxlength'	 => true]);
			?>
		</div>
	</div>
<?php endif; ?>

<div class="row">
	
	<div class="col-md-3">
		<?=
		$form->field($model, '[' . $model->tabModulos->id . ']num_secretaria_fone')->widget(MaskedInput::className(), [
			'mask' => '(99) 9999-99999',
		])->textInput(['placeholder'	 => "(99) 9999-99999",
			'name'			 => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][num_secretaria_fone]',
			'maxlength'		 => true])
		?>
	</div>
	<div class="col-md-3">
		<?= $form->field($model, '[' . $model->tabModulos->id . ']num_secretaria_ramal')->textInput(['name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][num_secretaria_ramal]', 'maxlength' => true]) ?>
	</div>
	<div class="col-md-3">
		<?=
		$form->field($model, '[' . $model->tabModulos->id . ']num_secretaria_fone2')->widget(MaskedInput::className(), [
			'mask' => '(99) 9999-99999',
		])->textInput(['placeholder'	 => "(99) 9999-99999",
			'name'			 => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][num_secretaria_fone2]',
			'maxlength'		 => true])
		?>
	</div>
		<div class="col-md-3">
		<?= $form->field($model, '[' . $model->tabModulos->id . ']num_secretaria_ramal2')->textInput(['name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][num_secretaria_ramal2]', 'maxlength' => true]) ?>
	</div>
</div>

<div class="row">

	<div class="col-md-3">
		<?=
		$form->field($model, '[' . $model->tabModulos->id . ']num_secretaria_fax')->widget(MaskedInput::className(), [
			'mask' => '(99) 9999-99999',
		])->textInput(['placeholder'	 => "(99) 9999-99999",
			'name'			 => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][num_secretaria_fax]',
			'maxlength'		 => true])
		?>
	</div>
	<div class="col-md-3">
		<?= $form->field($model, '[' . $model->tabModulos->id . ']num_secretaria_ramal_fax')->textInput(['name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][num_secretaria_ramal_fax]', 'maxlength' => true]) ?>
	</div>
		<div class="col-md-3">
		<?= $form->field($model, '[' . $model->tabModulos->id . ']txt_secretaria_email')->textInput(['name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][txt_secretaria_email]', 'maxlength' => true]) ?>
	</div>
	<div class="col-md-3">
		<?= $form->field($model, '[' . $model->tabModulos->id . ']txt_secretaria_email2')->textInput(['name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][txt_secretaria_email2]', 'maxlength' => true]) ?>
	</div>
</div>	


<div class="row">

</div>	
<?php if (in_array($this->context->module->id, ['admin', 'gestao'])) : ?>
	<div class="row">
		<div class="col-md-12">
			<?=
			$form->field($model, "[" . $model->tabModulos->id . "]txt_observacoes")->textarea(
				['maxlength' => true, 'name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][txt_observacoes]',]
			)
			?>
		</div>
	</div>	
<?php endif; ?>

<div class="row">
	<div class="col-md-12">
		<h3>Contatos</h3>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<?=
		TabsX::widget([
			'items'			 => [
				[
					'label'			 => 'Responsável',
					'headerOptions'	 => ['id' => $model->tabModulos->id . '_responsavel'],
					'content'		 => $this->render('_form_contato', ['form' => $form, 'model' => $model, 'tipo_prestador' => $tipo_prestador, 'tipo' => 'responsavel', 'key' => $key]),
				],
				[
					'label'			 => 'Encarregado',
					'headerOptions'	 => ['id' => $model->tabModulos->id . '_encarregado'],
					'content'		 => $this->render('_form_contato', ['form' => $form, 'model' => $model, 'tipo_prestador' => $tipo_prestador, 'tipo' => 'encarregado', 'key' => $key]),
				],
				[
					'label'			 => 'Substituto',
					'headerOptions'	 => ['id' => $model->tabModulos->id . '_outros'],
					'content'		 => $this->render('_form_contato', ['form' => $form, 'model' => $model, 'tipo_prestador' => $tipo_prestador, 'tipo' => 'outro', 'key' => $key]),
				]
			],
			'position'		 => TabsX::POS_ABOVE,
			'bordered'		 => true,
			'encodeLabels'	 => false,
			'options'		 => ['id' => 'contatos_modulos_' . $model->tabModulos->id],
		]);
		?>
	</div>
</div>