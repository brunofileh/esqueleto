<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabAcoes */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile("@web/js/app/admin.iniciar-coleta.js?{$this->app->version}", ['position' => $this::POS_END, 'depends' => [\app\assets\ProjetoAsset::className()]]);
?>

<div class="tab-acoes-form box box-default">
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="box-header with-border">
		<h3 class="box-title">Layout de importação: { COD. MUNICÍPIO | ANO REF. AE | SIT. AE |  ANO REF. RS | SIT. RS }</h3>
		<div class="box-tools">
			<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . 'Abrir arquivo', ['style' => 'display:' . (($importacao && (!$importacao['is_valid_layout'] )) ? 'none' : 'block'), 'name' => 'abrir', 'class' => 'btn btn-success btn-sm']) ?>
			<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . 'Importar', ['style' => 'display:' . (($importacao && (!$importacao['is_valid_layout'] )) ? 'block' : 'none' ), 'name' => 'importar', 'class' => 'btn btn-primary btn-sm']) ?>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-3">
					<?= $form->field($model, 'ano_ref')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>
				</div>
			</div>		
			<div class="row">
				<div class="col-md-12">
					<?= $form->field($model, 'file')->fileInput() ?>
				</div>
			</div>	
			<br />
			<div id="importacao">
				<?php if ($importacao) : ?>
					<hr>
					<div class="row">
						<div class="col-md-12">
							<?= '<b>Total de Municípios: ' . $importacao['total'] . '</b>'; ?>

						</div>
					</div>	
					<br />
					<div class="row">
						<div class="col-lg-12"> 


							<?=
							yii\grid\GridView::widget([
								'dataProvider' => $importacao['dataProvider'],
								//'filterModel' => $searchModel,
								'columns' => [

									[
										'label' => 'Código Município',
										'value' => function ($model) {

											return $model['cod_municipio_fk'];
										},
									],
									[
										'label' => 'Ano AE',
										'value' => function ($model) {

											return $model['snis_ae_ano'];
										},
									],
									[
										'label' => 'Situação AE',
										'value' => function ($model) {

											return $model['dsc_descricao_ae'];
										},
									],
									[

										'label' => 'Ano RS',
										'value' => function ($model) {

											return $model['snis_rs_ano'];
										},
									],
									[

										'label' => 'Situação RS',
										'value' => function ($model) {

											return $model['dsc_descricao_rs'];
										},
									],
								],
							]);
							?>

						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="box-footer">
			<h3 class="box-title"></h3>
			<div class="box-tools pull-right">
				<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . 'Abrir arquivo', ['style' => 'display:' . (($importacao && (!$importacao['is_valid_layout'] )) ? 'none' : 'block'), 'name' => 'abrir', 'class' => 'btn btn-success btn-sm']) ?>
				<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . 'Importar', ['style' => 'display:' . (($importacao && (!$importacao['is_valid_layout'] )) ? 'block' : 'none' ), 'name' => 'importar', 'class' => 'btn btn-primary btn-sm']) ?>
			</div>
		</div>
		<?php ActiveForm::end(); ?>

	</div>
</div>
