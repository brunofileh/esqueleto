<?php
use yii\helpers\Html;
use app\models\TabPrestadoresSearch;
use app\models\TabMunicipiosSearch;
use yii\bootstrap\Modal;

?>

<?php
    $municipio = TabPrestadoresSearch::find()
        ->select([TabMunicipiosSearch::tableName() . '.txt_nome', TabMunicipiosSearch::tableName() . '.sgl_estado_fk'])
        ->innerJoin(TabMunicipiosSearch::tableName(), TabMunicipiosSearch::tableName() . '.cod_municipio = ' . TabPrestadoresSearch::tableName() . '.cod_municipio_fk')
        ->where(['=', TabPrestadoresSearch::tableName() . '.cod_prestador', $this->user->identity->attributes['cod_prestador_fk']])
        ->andWhere(TabPrestadoresSearch::tableName() . '.dte_exclusao IS NULL')
        ->asArray()
        ->one();
    $dsc_municipio = $municipio['txt_nome'] . ' - ' . $municipio['sgl_estado_fk'];
?>

<?php Modal::begin([
	'headerOptions'	 => ['id' => 'modalHeader'],
	'header'		 => '<h3>Bem vindo(a) ao SNIS - Sistema Nacional de Informações sobre Saneamento<h3>',
	'id'			 => 'modal',
	'size'			 => 'modal-lg',
	'closeButton'    => false,      
	'footer'		 =>
		Html::a('Não', ['home', 'resposta' => 'N'], ['class' => 'btn btn-default',])
		. PHP_EOL .
		Html::a('Sim', ['home', 'resposta' => 'S'], ['class' => 'btn btn-default btn-modal-save']),
	'clientOptions'	 => ['backdrop' => 'static', 'keyboard' => false]
]) ?>
	
	<div>
		<h4 class="box-title">Registro de novos usuários</h4>
		<br />
		<p>Prezado(a) <b>"<?= $this->user->identity->txt_nome; ?>"</b>,</p>
		<p>
			Ao realizar o primeiro acesso ao sistema em nome do município de <b><?= $dsc_municipio ?></b>, 
			você recebeu o perfil de "Responsável pela Informação" da coleta de dados sobre Drenagem e Manejo 
			das Águas Pluviais Urbanas. Com este perfil, você pode registrar novos usuários, os quais também 
			poderão acessar e preencher os formulários. Note que apenas o Responsável pela Informação pode 
			finalizar a coleta de dados. Deseja registrar novos usuários agora?
		</p>
	</div>

<?php Modal::end() ?>

<?php 
	$js = "$('#modal').modal('show')";
    $this->registerJs($js, \yii\web\View::POS_LOAD); 
?>