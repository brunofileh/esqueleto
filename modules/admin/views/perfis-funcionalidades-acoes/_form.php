<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\RlcPerfisFuncionalidadesAcoes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rlc-perfis-funcionalidades-acoes-form box box-default">
	<?php 
	
	
	$form			 = ActiveForm::begin(); ?>

    <div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
			<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')]) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Cancelar', Url::to(['funcionalidades/index', 'cod_modulo'=>$cod_modulo]), ['class' => 'btn btn-default btn-sm']) ?>
		</div>
    </div>

	<div class="box-body">
		<?php
		
		echo $form->field($model, 'cod_perfil_fk')
			->dropDownList($listaPerfis, [
				'prompt'	 => $this->app->params['txt-prompt-select'],
				'onchange'	 => '
                    $.get( "' . Url::toRoute('perfis-funcionalidades-acoes/listar-acao') . '", {
                            cod_perfil_fk: $(this).val() , cod_funcionalidade_fk: ' . $model->cod_funcionalidade_fk . '
                        } )
                        .done(function( data ) {
							
							$("#dlb-lista_acoes").html(data);
                        }
                    );'
		]);
		?>

    </div>

	<div id="dlb-lista_acoes">
		<?php
	
		if ($model->cod_perfil_fk) {

			$js =  '
                    $.get( "' . Url::toRoute('perfis-funcionalidades-acoes/listar-acao') . '", {
                            cod_perfil_fk: $("#rlcperfisfuncionalidadesacoessearch-cod_perfil_fk").val() , cod_funcionalidade_fk: ' . $model->cod_funcionalidade_fk . '
                        } )
                        .done(function( data ) {
							
							$("#dlb-lista_acoes").html(data);
                        }
                    );';
            
			$this->registerJs($js, \yii\web\View::POS_LOAD);
		}
		?>

	</div>

	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')]) ?>
			<?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Cancelar', Url::to(['funcionalidades/index', 'cod_modulo'=>$cod_modulo]), ['class' => 'btn btn-default btn-sm']) ?>
		</div>
    </div>
	<?php ActiveForm::end(); ?>
</div>
