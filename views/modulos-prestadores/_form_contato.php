<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\TabAtributosValoresSearch;
use app\models\TabAtributosSearch;
use yii\widgets\MaskedInput;
use app\modules\admin\models\TabModulosSearch;

/* @var $this yii\web\View */
/* @var $model app\models\TabPrestadores */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
	<div class="col-md-6">
		<?= $form->field($model, "[" . $model->tabModulos->id . "]txt_{$tipo}_nome")->textInput(['name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][txt_' . $tipo . '_nome]', 'maxlength' => true]) ?>

	</div>
	<div class="col-md-6">
		<?= $form->field($model, "[" . $model->tabModulos->id . "]txt_{$tipo}_cargo")->textInput(['name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][txt_' . $tipo . '_cargo]', 'maxlength' => true]) ?>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<?= $form->field($model, "[" . $model->tabModulos->id . "]txt_{$tipo}_genero")->dropDownList(TabAtributosValoresSearch::getAtributoValor(TabAtributosSearch::TIPO_GENERO, true), ['prompt' => '-- selecione --', 'name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][txt_' . $tipo . '_genero]', 'maxlength' => true]) ?>
	</div>
	<div class="col-md-6">
		<?= $form->field($model, "[" . $model->tabModulos->id . "]txt_{$tipo}_tratamento")->dropDownList(TabAtributosValoresSearch::getAtributoValor(TabAtributosSearch::PRONOME_TRATAMENTO, true), ['prompt' => '-- selecione --', 'name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][txt_' . $tipo . '_tratamento]', 'maxlength' => true]) ?>
	</div>

</div>

<?php if (in_array($this->context->module->id, ['admin', 'gestao'])) : ?>
	<div class="row" style="<?= (($tipo_prestador == 'P') ? 'display:block' : 'display:none') ?>">
		<div class="col-md-6">
			<?= $form->field($model, "[" . $model->tabModulos->id . "]txt_{$tipo}_partido")->dropDownList(TabAtributosValoresSearch::getAtributoValor(TabAtributosSearch::PARTIDO_POLITICO, true), ['prompt' => '-- selecione --', 'name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][txt_' . $tipo . '_partido]', 'maxlength' => true]) ?>
		</div>
		<div class="col-md-6">
			<?= $form->field($model, "[" . $model->tabModulos->id . "]txt_{$tipo}_sucessao")->dropDownList(TabAtributosValoresSearch::getAtributoValor(TabAtributosSearch::SUCESSAO_CARGO_POLITICO, true), ['prompt' => '-- selecione --', 'name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][txt_' . $tipo . '_sucessao]', 'maxlength' => true]) ?>
		</div>
	</div>
<?php endif; ?>


<div class="row">
	<div class="col-md-3">
		<?=
		$form->field($model, "[" . $model->tabModulos->id . "]num_{$tipo}_fone")->widget(MaskedInput::className(), [
			'mask' => '(99) 9999-99999',
		])->textInput(['placeholder'	 => "(99) 9999-99999",
			'name'			 => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][num_' . $tipo . '_fone]',
			'maxlength'		 => true])
		?>
	</div>
	<div class="col-md-3">
		<?=
		$form->field($model, "[" . $model->tabModulos->id . "]num_{$tipo}_ramal")->textInput([
			'name'		 => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][num_' . $tipo . '_ramal]',
			'maxlength'	 => true])
		?>
	</div>
	<div class="col-md-3">
		<?=
		$form->field($model, "[" . $model->tabModulos->id . "]num_{$tipo}_fone2")->widget(MaskedInput::className(), [
			'mask' => '(99) 9999-99999',
		])->textInput(['placeholder'	 => "(99) 9999-99999",
			'name'			 => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][num_' . $tipo . '_fone2]',
			'maxlength'		 => true])
		?>
	</div>

	<div class="col-md-3">
		<?=
		$form->field($model, "[" . $model->tabModulos->id . "]num_{$tipo}_ramal2")->textInput([
			'name'		 => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][num_' . $tipo . '_ramal2]',
			'maxlength'	 => true])
		?>
	</div>
</div>

<div class="row">
	<div class="col-md-3">
		<?=
		$form->field($model, "[" . $model->tabModulos->id . "]num_{$tipo}_fax")->widget(MaskedInput::className(), [
			'mask' => '(99) 9999-99999',
		])->textInput(['placeholder'	 => "(99) 9999-99999",
			'name'			 => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][num_' . $tipo . '_fax]',
			'maxlength'		 => true])
		?>
	</div>
	<div class="col-md-3">
		<?=
		$form->field($model, "[" . $model->tabModulos->id . "]num_{$tipo}_ramal_fax")->textInput([
			'name'		 => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][num_' . $tipo . '_ramal_fax]',
			'maxlength'	 => true])
		?>
	</div>

	<div class="col-md-3">
		<?= $form->field($model, "[" . $model->tabModulos->id . "]txt_{$tipo}_email")->textInput(['name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][txt_' . $tipo . '_email]', 'maxlength' => true]) ?>
	</div>
	<div class="col-md-3">
		<?= $form->field($model, "[" . $model->tabModulos->id . "]txt_{$tipo}_email2")->textInput(['name' => 'RlcModulosPrestadoresSearch[' . $key . '][RlcModulosPrestadoresSearch][txt_' . $tipo . '_email2]', 'maxlength' => true]) ?>
	</div>
</div>





