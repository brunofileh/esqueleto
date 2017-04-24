<?php

use yii\helpers\Html;

yii\bootstrap\Modal::begin([
	'headerOptions'	 => ['id' => 'modalHeader'],
	'header'		 => '<h3><i class="icon fa fa-warning"></i> Alerta de Sessão<h3>',
	'id'			 => 'modal',
	'closeButton'	 => false,
	'size'			 => 'modal-lg',
	'footer'		 =>
	Html::a('Nao', [\yii\helpers\Url::to('/site/atualiza-login'), 'resposta' => 'N', 'url' => \yii\helpers\Url::current()], ['class' => 'btn btn-default', 'data-method' => 'post'])
	. PHP_EOL .
	Html::a('Sim', [\yii\helpers\Url::to('/site/atualiza-login'), 'resposta' => 'S', 'url' => \yii\helpers\Url::current()], ['class' => 'btn btn-success btn-modal-save', 'data-method' => 'post']),
	//keeps from closing modal with esc key or by clicking out of the modal.
	// user must click cancel or X to close
	'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
]);
?>


<div>
	<h3 class="box-title">Login Duplicado</h3>
	<br />

	<p><b>Prezado <?= $this->user->identity->txt_nome; ?>,</b></p>
	<p>Já existe uma terminal logado com seu usuário e senha. Deseja logar nesse terminal?</p>

</div>

<?php yii\bootstrap\Modal::end(); ?>

<?php $js = " $('#modal').modal('show')
                    .find('#modalContent')
                    .load($(this).attr('value'));";
            
$this->registerJs($js, \yii\web\View::POS_LOAD); ?>