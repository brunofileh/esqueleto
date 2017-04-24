<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use dmstr\widgets\Alert;

$this->registerJs('$("#mdlusuarios-txt_nome").focus();', $this::POS_READY);

?>

<?= Alert::widget(); ?>

<div class="register-box">
	<div class="register-logo">

        <h3>Registro de novo usuário</h3>
	</div>

	<div class="register-box-body">

		<?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off']]); ?>

		<?php
		$disabled = null;

		if ($cod_prestador) {
			$disabled = 'disabled';
		}
		?>

		<div class="form-group has-feedback">
			<div class="row">
				<div class="col-xs-6">
					<?php
					if ($cod_prestador) {
						echo Html::hiddenInput('MdlUsuarios[municipio]', $model->municipio, ['id' => 'municipio']);
					}
					echo $form->field($model, 'municipio')->dropDownList(
						ArrayHelper::map($listaMunicipio, 'cod_municipio', 'txt_nome'), ['id' => 'municipio',
						'prompt' => '-- selecione --',
						'disabled' => $disabled,
						'onchange' => '
                        $.post( "' . Yii::$app->urlManager->createUrl('prestadores/lista?codMunicipio=') . '"+$(this).val(), function( data ) {
                          $( "select#prestador" ).html( data );

                        });'
					]);
					?>
				</div>
				<div class="col-xs-6">            
					<?php
					if ($cod_prestador) {
						echo Html::hiddenInput('MdlUsuarios[cod_prestador_fk]', $model->cod_prestador_fk, ['id' => 'prestador']);
					}

					echo $form->field($model, 'cod_prestador_fk')->dropDownList(
						ArrayHelper::map($listaPrestador, 'cod_prestador', 'dg002')
						, ['id' => 'prestador',
						'prompt' => '-- selecione --',
						'disabled' => $disabled,
						'onchange' => '
                           $.post( "' . Yii::$app->urlManager->createUrl('admin/modulos/lista?codPrestador=') . '"+$(this).val(), function( data ) {
                             $( "#modulos" ).html( data );

                           });'
					]);
					?>
				</div>
			</div>
			<div class="form-group has-feedback">
				<div class="row">
					<div class="col-xs-6">            
						<?=
						$form->field($model, 'modulos')->dropDownList(ArrayHelper::map($listaModulo, 'cod_modulo', 'dsc_modulo'), [
							'id' => 'modulos',
							//'multiple' => 'multiple',
						])
						?>
					</div>
					<div class="col-xs-6">
						<?php
						if ($cod_prestador) {
							echo Html::hiddenInput('MdlUsuarios[cod_perfil_fk]', $model->cod_perfil_fk, ['id' => 'cod_perfil_fk']);
						}

						echo $form->field($model, 'cod_perfil_fk')->dropDownList(
							ArrayHelper::map($listaPerfil, 'cod_perfil', 'txt_nome')
							, ['id' => 'cod_perfil_fk',
							'prompt' => '-- selecione --',
							'disabled' => $disabled,
						]);
						?>
					</div>
				</div>            
			</div>
			<div class="form-group has-feedback">
				<div class="row">
					<div class="col-xs-8">
						<?= $form->field($model, 'txt_nome')->textInput(['class' => 'form-control']); ?>
					</div>
					<div class="col-xs-4">
						<?=
						$form->field($model, 'num_cpf')->widget(\yii\widgets\MaskedInput::className(), [
							'mask' => '999.999.999-99',
						])->textInput(['class' => 'form-control']);
						?>
					</div>

				</div>
			</div>        
     
			<div class="form-group has-feedback">
				<div class="row">
					<div class="col-xs-8">
						<?= $form->field($model, 'txt_email')->textInput(['class' => 'form-control']); ?>
					</div>
					<div class="col-xs-4">
						<?=
						$form->field($model, 'num_fone')->widget(\yii\widgets\MaskedInput::className(), [
							'mask' => ['(99) 9999-9999', '(99) 99999-9999'],
						])->textInput(['class' => 'form-control']);
						?>
					</div>
				</div>
			</div>      
			<div class="form-group has-feedback">
				<div class="row">
					
					<div class="col-xs-4">
						<?= $form->field($model, 'txt_login')->textInput(['class' => 'form-control']); ?>
					</div>   
					<div class="col-xs-4">
						<?= $form->field($model, 'txt_senha')->passwordInput() ?>
					</div>
					<div class="col-xs-4">
						<?= $form->field($model, 'txt_senha_confirma')->passwordInput() ?>
					</div>	

				</div>
			</div>
			<div class="row">
				<div class="col-xs-6">
					<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> ' . 'Cancelar', 'entrar', ['class' => 'btn btn-primary btn-block btn-flat']) ?>
				</div>
				<div class="col-xs-6">
					<?= Html::submitButton(' <i class="glyphicon glyphicon-ok"></i> ' . 'Registrar', ['class' => 'btn btn-success btn-block btn-flat', 'name' => 'register-button']) ?>
				</div>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>