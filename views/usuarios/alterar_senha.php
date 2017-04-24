<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabUsuarios */
/* @var $form yii\widgets\ActiveForm */

?>
<?php $this->beginBlock('conteudo-principal') ?>
<div class="tab-modulos-form box box-default">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box-header with-border">
        <h3 class="box-title"></h3>
        <div class="box-tools">
            <?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> Alterar', ['class' => 'btn btn-primary btn-sm']) ?>
            <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Cancelar', ['view'], ['class' => 'btn btn-default btn-sm']) ?>
        </div>
    </div>

    <div class="box-body">
        <?= $form->field($model, 'txt_senha_atual')->passwordInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'txt_senha')->passwordInput(['maxlength' => true]) ?>
		<?= $form->field($model, 'txt_senha_confirma')->passwordInput(['maxlength' => true]) ?>
    </div>
	
    <div class="box-footer">
        <h3 class="box-title"></h3>
        <div class="box-tools pull-right">
            <?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> Alterar', ['class' => 'btn btn-primary btn-sm']) ?>
            <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Cancelar', ['view'], ['class' => 'btn btn-default btn-sm']) ?>
        </div>
    </div>
	
    <?php ActiveForm::end(); ?>
	
</div>
<?php $this->endBlock() ?>