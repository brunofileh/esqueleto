<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use projeto\helpers\Html;
use projeto\web\View;
use dmstr\widgets\Alert;
?>

<?= Alert::widget(); ?>

<div class="login-box">
    <div class="login-logo">
        <?php
        $img  = Html::img('@web/img/layout/head_logo1.png', [
                'class' => 'img-circle',
                'alt'   => 'SNIS - Sistema Nacional de Informações sobre Saneamento'
        ]);
        echo Html::a($img, Url::base(), ['class' => 'logo']);
        ?>
        <h3>Recuperar senha</h3>
    </div>

    <div class="login-box-body">

        <p class="">Preencha o campo abaixo com seu e-mail caso você tenha esquecido sua senha. Será enviado um e-mail com um link para redefinir sua senha.</p>
        <p>&nbsp;</p>

        <?php
        $form = ActiveForm::begin([
                'id'          => 'recuperar-senha-form',
                'fieldConfig' => [
                    'template'     => "<div>{input}</div>\n<div>{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-1 control-label'],
                ],
        ]);
        ?>
        <div class="form-group has-feedback">
            <?= $form->field($model, 'txt_email')->textInput(['placeholder' => $model->getAttributeLabel("txt_email"), 'autocomplete' => 'off']); ?>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <?= $form->field($model, 'txt_email_confirma')->textInput(['placeholder' => $model->getAttributeLabel("txt_email_confirma"), 'autocomplete' => 'off', 'onpaste' => 'return false']) ?>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>

        <div class="form-group">

            <div class="row">
                <div class="col-xs-6">
                    <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> ' . 'Voltar', 'entrar', ['class' => 'btn btn-primary btn-block btn-flat']) ?>
                </div>
                <div class="col-xs-6">
                    <?= Html::submitButton('Recuperar senha' . ' <i class="glyphicon glyphicon-log-in"></i> ', ['class' => 'btn btn-success btn-block btn-flat', 'name' => 'register-button']) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php $this->registerJs("$('#tabusuariossearch-txt_email').focus();", View::POS_END) ?>