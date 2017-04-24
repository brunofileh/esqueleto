<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerJsFile("@web/js/app/admin.iniciar-coleta.js?{$this->app->version}", [
    'position' => $this::POS_END,
    'depends'  => [\app\assets\ProjetoAsset::className()]
]);

?>

<div class="tab-municipios-desastres-form box box-default">
	
	<?php
        $form = ActiveForm::begin([
            'options' => [
				'enctype' => 'multipart/form-data'
			]
        ]);
    ?>
    
    <div class="box-header with-border">
		<h3 class="box-title">
            Layout de importação: <b>{Código IBGE |  Código do município | Município | UF | Tipo de desastre | Desastres | Óbitos | Desabrigados | Desalojados}</b>
        </h3>
		<div class="box-tools">
			<?= Html::submitButton('<i class="glyphicon glyphicon-arrow-up"></i> ' . 'Abrir arquivo', ['style' => 'display: ' . ((!$importacao) ? 'block' : 'none'), 'name' => 'abrir_arquivo', 'class' => 'btn btn-primary btn-sm']) ?>
			<?= Html::submitButton('<i class="glyphicon glyphicon-arrow-down"></i> ' . 'Importar dados', ['style' => 'display: ' . (($importacao) ? 'block' : 'none'), 'name' => 'importar_dados', 'class' => 'btn btn-success btn-sm']) ?>
		</div>
    </div>
    
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'file')->fileInput() ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'ano_ref')->textInput(['maxlength' => true, 'readonly' => (($importacao) ? true : false)]) ?>
            </div>
        </div>
		
		<?php if ($importacao) : ?>
			<div id="importacao">
                <hr />
                <div class="row">
                    <div class="col-lg-12">
                        <?= yii\grid\GridView::widget([
                            'dataProvider' => $importacao,
                            'columns'      => [
                                [
                                    'label' => 'Código IBGE',
                                    'value' => function ($model) {
                                        return $model['cod_ibge'];
                                    },
                                ],
                                [
                                    'label' => 'Código do município',
                                    'value' => function ($model) {
                                        return $model['cod_municipio'];
                                    },
                                ],
                                [
                                    'label' => 'Município',
                                    'value' => function ($model) {
                                        return $model['nome_municipio'];
                                    },
                                ],
                                [
                                    'label' => 'UF',
                                    'value' => function ($model) {
                                        return $model['uf'];
                                    },
                                ],
                                [
                                    'label' => 'Tipo de desastre',
                                    'value' => function ($model) {
										if ($model['tipo_desastre'] == 'A')
											$tp = 'Alagamento';
										else if ($model['tipo_desastre'] == 'E')
											$tp = 'Enxurrada';
										else if ($model['tipo_desastre'] == 'I')
											$tp = 'Inundação';
                                        return $tp;
                                    },
                                ],
                                [
                                    'format' => ['integer'],
                                    'label'  => 'Desastres',
                                    'value'  => function ($model) {
                                        return $model['num_desastres'];
                                    },
                                ],
                                [
                                    'format' => ['integer'],
                                    'label'  => 'Óbitos',
                                    'value'  => function ($model) {
                                        return $model['num_obitos'];
                                    },
                                ],
                                [
                                    'format' => ['integer'],
                                    'label'  => 'Desabrigados',
                                    'value'  => function ($model) {
                                        return $model['num_desabrigados'];
                                    },
                                ],
                                [
                                    'format' => ['integer'],
                                    'label'  => 'Desalojados',
                                    'value'  => function ($model) {
                                        return $model['num_desalojados'];
                                    },
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
		<?php endif; ?>
    </div>
    
    <div class="box-footer">
        <h3 class="box-title"></h3>
        <div class="box-tools pull-right">
			<?= Html::submitButton('<i class="glyphicon glyphicon-arrow-up"></i> ' . 'Abrir arquivo', ['style' => 'display: ' . ((!$importacao) ? 'block' : 'none'), 'name' => 'abrir_arquivo', 'class' => 'btn btn-primary btn-sm']) ?>
			<?= Html::submitButton('<i class="glyphicon glyphicon-arrow-down"></i> ' . 'Importar dados', ['style' => 'display: ' . (($importacao) ? 'block' : 'none'), 'name' => 'importar_dados', 'class' => 'btn btn-success btn-sm']) ?>
        </div>
    </div>
    
	<?php ActiveForm::end(); ?>
    
</div>

<div class="tab-municipios-desastres-form box box-default">
    <div class="box-header with-border">
		<h3 class="box-title">
            Desastres importados por ano de referência
        </h3>
		<div class="box-tools"></div>
    </div>
	
	<div class="box-body">
		<div class="row">
			<div class="col-lg-12">
				<?= yii\grid\GridView::widget([
					'dataProvider' => $desastres_importados,
					'columns'      => [
						[
							'label' => 'Ano de referência',
							'value' => function ($model) {
								return $model['ano_ref'];
							},
						],
						[
							'label' => 'Tipo de desastre',
							'value' => function ($model) {
								if ($model['tipo_desastre'] == 'A')
									$tp = 'Alagamento';
								else if ($model['tipo_desastre'] == 'E')
									$tp = 'Enxurrada';
								else if ($model['tipo_desastre'] == 'I')
									$tp = 'Inundação';
								return $tp;
							},
						],
						[
							'format' => ['integer'],
							'label'  => 'Desastres',
							'value'  => function ($model) {
								return $model['num_desastres'];
							},
						],
						[
							'format' => ['integer'],
							'label'  => 'Óbitos',
							'value'  => function ($model) {
								return $model['num_obitos'];
							},
						],
						[
							'format' => ['integer'],
							'label'  => 'Desabrigados',
							'value'  => function ($model) {
								return $model['num_desabrigados'];
							},
						],
						[
							'format' => ['integer'],
							'label'  => 'Desalojados',
							'value'  => function ($model) {
								return $model['num_desalojados'];
							},
						],
					],
				]); ?>
			</div>
		</div>
	</div>
</div>
