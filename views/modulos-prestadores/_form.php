<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $model app\models\TabPrestadores */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$form = ActiveForm::begin([
		'validateOnSubmit'		 => false,
		'enableClientValidation' => true,
	]);
?>

<div class="tab-prestadores-form box box-default">

    <div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
			<?php if ($model->rlcModulosPrestadores) echo Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')]) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Yii::$app->request->referrer, ['class' => 'btn btn-default btn-sm']) ?>
		</div>
    </div>

	<div class="box-body">
		<?php
		if ($model->rlcModulosPrestadores) :
			foreach ($model->rlcModulosPrestadores as $key => $value) {
				$tab[] = [
					'headerOptions'	 => ['id' => $value->tabModulos->id],
					'label'			 => $value->tabModulos->txt_nome,
					'content'		 => $this->render('_form_modulos', ['form' => $form, 'model' => $value, 'key' => $key, 'tipo_prestador'=>$model->txt_tipo_prestador]),
				];
			}
			?>
			<?=
			TabsX::widget([
				'items'			 => $tab,
				'position'		 => TabsX::POS_ABOVE,
				'bordered'		 => true,
				'encodeLabels'	 => false,
				'options'		 => ['id' => 'modulos'],
			]);

		else :	?>
			<span ><p><i class="fa fa-warning"></i> Não existe módulo vinculado para o prestador <b><?= $model->txt_nome ?></b></p></span>
		<?php endif; ?>
	</div>

	<div class="box-footer">
		<div class="box-tools pull-right">
			<?php if ($model->rlcModulosPrestadores) echo Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')]) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Yii::$app->request->referrer, ['class' => 'btn btn-default btn-sm']) ?>
		</div>
    </div>

</div>
<?php ActiveForm::end(); ?>

<?php
if (Yii::$app->user->identity->txt_tipo_login == '3') {
	
	$js = "projeto.alert('Atenção: Favor atualizar dados do Órgão responsável pela drenagem no município');";

	$this->registerJs($js, \yii\web\View::POS_LOAD);
}
?>