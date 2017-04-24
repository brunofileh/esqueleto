<?php


$atr = app\models\TabAtributos::findOneAsArray(["sgl_chave"=>'contato-situacao'])['cod_atributos'];

$valor = app\models\TabAtributosValoresSearch::findOne(['fk_atributos_valores_atributos_id'=>$atr, 'sgl_valor'=>'3']);

 
if (!empty($this->session->get('TabParticipacoes'))) {
	//$usuario = app\models\VisConsultaPrestadoresSearch::findOne(['cod_participacao' => $this->session->get('TabParticipacoes')['cod_participacao']]);

	$usuario = app\modules\drenagem\models\TabParticipacoes::findOne(['cod_participacao' => $this->session->get('TabParticipacoes')['cod_participacao']]);

	$contatos = app\models\TabContatosSearch::find()->where("cod_situacao_fk<> {$valor->cod_atributos_valores} AND cod_prestador_fk={$usuario->rlcModulosPrestadores->cod_prestador_fk} AND dte_exclusao is null AND  cod_modulo_fk={$this->context->module->getInfo()['cod_modulo']}")->all();
} else {
	$contatos = app\models\TabContatosSearch::find()->where("cod_situacao_fk<> {$valor->cod_atributos_valores} AND dte_exclusao is null AND  cod_modulo_fk={$this->context->module->getInfo()['cod_modulo']}")->all();
}

?>


<li class="dropdown messages-menu">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		<i class="fa fa-envelope-o"></i>
		<?php if (($c=count($contatos)) > 0): ?>
			<span class="label label-danger"><?= $c; ?></span>
		<?php endif ?>
	</a>
	<ul class="dropdown-menu"><li>
		<?php if (!empty($this->session->get('TabParticipacoes'))) : ?>
			<a href="javascript://;" onclick='projeto.showPopupContatos("<?= yii\helpers\Url::to('drenagem/contatos/admin') ?>");' ><i class="menu-icon glyphicon glyphicon-plus"></i> Incluir novo contato</a>
		
		<?php else: ?>
			<a href="<?= yii\helpers\Url::toRoute(["contatos/index", 'prefeitura'=>true]) ?>"><i class="menu-icon glyphicon glyphicon-plus"></i> Incluir novo contato</a>
		<?php endif; ?>
		</li>
		<li>
			<a href="<?= yii\helpers\Url::to('drenagem/contatos') ?>"><i class="menu-icon fa fa-search"></i> Consultar contatos</a>
		</li>


	</ul>
</li>