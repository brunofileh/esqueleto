<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\TabAtributosValoresSearch;
use app\models\TabAtributosSearch;
use \cyneek\yii2\widget\upload\crop\UploadCrop;

$js = " 
	 setTimeout(function () {
			$('#tabusuariossearch-num_cpf').focus();
			
		}, 300);
	
";

$this->registerJs($js, $this::POS_BEGIN);

$this->registerJsFile("@web/js/app/admin.usuarios.js?{$this->app->version}", ['position' => $this::POS_END, 'depends' => [\app\assets\ProjetoAsset::className()]]);

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabUsuarios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tab-modulos-form box box-default">

	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="box-header with-border">
        <h3 class="box-title">Informações Gerais</h3>
        <div class="box-tools">
			<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')]) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Cancelar', Url::toRoute($infoModulo['menu-item']['txt_url']), ['class' => 'btn btn-default btn-sm']) ?>
        </div>

    </div>
    <div class="box-body">
		<div class="row">
			<div class="col-md-12">
				<?=
				Html::img($model->txt_imagem, [
					'class' => 'img-circle',
					'width' => '80px',
					'height' => '80px',
				])
				?>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<?=
				$form->field($model, 'num_cpf')->widget(MaskedInput::className(), [
					'mask' => ['999.999.999-99'],
				])
				?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">	
				<?= $form->field($model, 'txt_login')->textInput(['maxlength' => true]) ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<?= $form->field($model, 'txt_nome')->textInput(['maxlength' => true]) ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<?= $form->field($model, 'txt_email')->textInput(['maxlength' => true]) ?>
			</div>
		</div>

		<?php if (!$model->isNewRecord): ?>
			<div class="row">
				<div class="col-md-12">
					<?=
						$form->field($model, 'txt_ativo')
						->dropDownList(
							TabAtributosValoresSearch::getAtributoValor(TabAtributosSearch::OPT_SIM_NAO), ['prompt' => $this->app->params['txt-prompt-select']]
					);
					?>
				</div>
			</div>
		<?php endif; ?>

		<div class="row">
			<div class="col-md-12">
				<?=
					$form->field($model, 'txt_tipo_login')
					->dropDownList(
						TabAtributosValoresSearch::getAtributoValor(TabAtributosSearch::findOne([ 'sgl_chave' => 'tipo-usuario'])->cod_atributos), ['prompt' => $this->app->params['txt-prompt-select']]
					)
				?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<?=
				$form->field($model, 'num_fone')->widget(MaskedInput::className(), [
					'mask' => ['(99) 9999-9999', '(99) 99999-9999'],
				])
				?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<?= $form->field($model, 'txt_imagem_cropping')->widget(UploadCrop::className(), ['form' => $form])->label(false)->error(false); ?> 
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Informações de módulo perfil</h3>
					</div>

					<div class="box-body">
						<div class="row">
							<div class="col-md-5">
								<?=
								$form->field($model, 'cod_modulo')->dropDownList(
									yii\helpers\ArrayHelper::map($listaModulo, 'cod_modulo', 'txt_nome'
									), ['prompt' => $this->app->params['txt-prompt-select'],
								]);
								?>
							</div>
							<div class="col-md-5">
								<?=
								$form->field($model, 'cod_perfil')->dropDownList(
									yii\helpers\ArrayHelper::map($listaPerfil, 'cod_modulo', 'txt_nome'
									), ['prompt' => $this->app->params['txt-prompt-select'],
								]);
								?>
							</div>
							<div class="col-md-2" >
								<div class="form-group">
									<label class="control-label">&nbsp</label>
									<?=
									Html::button('<i class="glyphicon glyphicon-plus"></i> Adicionar perfil', [
										'id' => 'adicionarPerfil',
										'class' => 'btn btn-primary  btn-sm form-control',
									])
									?>

								</div>
							</div>
						</div>

					</div>

				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" id='grid-perfil-modulos'>
				<?=
				$this->render('/perfis/_grid')
				?>
			</div>
		</div>

	</div>
	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')]) ?>
	<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Cancelar', Url::toRoute($infoModulo['menu-item']['txt_url']), ['class' => 'btn btn-default btn-sm']) ?>
		</div>
	</div>
<?php ActiveForm::end(); ?>
</div>
