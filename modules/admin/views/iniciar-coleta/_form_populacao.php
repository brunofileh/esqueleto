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
		<h3 class="box-title">Layout de importação: {UF | COD. IBGE | COD. MUNICÍPIO | NOME MUNICÍPIO | POP. TOTAL | POP. URBANA}</h3>
		<div class="box-tools">
			<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . 'Abrir arquivo', ['style' => 'display:' . (($importacao && (!$importacao['is_valid_pop_tot'] && !$importacao['is_valid_rules'])) ? 'none' : 'block'), 'name' => 'abrir', 'class' => 'btn btn-success btn-sm']) ?>
			<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . 'Importar', ['style' => 'display:' . (($importacao && (!$importacao['is_valid_pop_tot'] && !$importacao['is_valid_rules'])) ? 'block' : 'none' ), 'name' => 'importar', 'class' => 'btn btn-primary btn-sm']) ?>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-3">
					<?= $form->field($model, 'ano_ult')->textInput(['disabled' => true]) ?>
				</div>
				<div class="col-md-3">
					<?= $form->field($model, 'ano_ref')->textInput(['maxlength' => true]) ?>
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

											return $model['municipio_fk'];
										},
									],
									[
										'label' => 'Nome do Município',
										'value' => function ($model) {

											return $model['nom_mun'];
										},
									],
									[
										'label' => 'UF',
										'value' => function ($model) {

											return $model['sgl_est'];
										},
									],
									[
										'format' => ['decimal'],
										'label' => 'População Total',
										'value' => function ($model) {

										return $model['pop_tot'];
									},
									],
									[
										'format' => ['decimal'],
										'label' => 'População Urbana',
										'value' => function ($model) {

										return $model['pop_urb'];
									},
									],
									[
										'format' => ['decimal'],
										'label' => 'População Rural',
										'value' => function ($model) {

										return $model['pop_rur'];
									},
									],
									[

										'label' => ' Taxa de Urbanização',
										'value' => function ($model) {

											return $model['tx_urb'];
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
				<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . 'Abrir arquivo', ['style' => 'display:' . (($importacao && (!$importacao['is_valid_pop_tot'] && !$importacao['is_valid_rules'])) ? 'none' : 'block'), 'name' => 'abrir', 'class' => 'btn btn-success btn-sm']) ?>
				<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . 'Importar', ['style' => 'display:' . (($importacao && (!$importacao['is_valid_pop_tot'] && !$importacao['is_valid_rules'])) ? 'block' : 'none' ), 'name' => 'importar', 'class' => 'btn btn-primary btn-sm']) ?>
			</div>
		</div>
		<?php ActiveForm::end(); ?>

	</div>
</div>
