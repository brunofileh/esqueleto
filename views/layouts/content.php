<?php

use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;
use yii\helpers\Inflector;
use app\models\TabAtributosValoresSearch;
use projeto\helpers\Html;
use app\modules\drenagem\models\TabParticipacoes;

?>

<div class="content-wrapper">
	<?php if (!Yii::$app->user->isGuest): ?>
		<section class="content-header">
                        
			<?php $this->beginBlock('bloco-titulo') ?>
				<?php if ($this->context->titulo !== null): ?>
					<?= Html::encode($this->context->titulo) ?>
				<?php else: ?>
					<?= $this->context->module->info['txt_nome'] ?>
					<?= ($this->context->module->id !== \Yii::$app->id) ? ' <small>M&oacute;dulo</small>' : '' ?>
				<?php endif ?>
			<?php $this->endBlock() ?>

			
				<div class="row">
					<div class="col-md-8" style="font-size:125%;"><?= $this->context->subTitulo ?></div>
					<div class="col-md-4 text-right">
			
					</div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<h1 style="margin: 0; font-size: 24px;">
							<?= $this->blocks['bloco-titulo'] ?>
						</h1>
					</div>
		
				</div>
			
		</section>
	<?php endif; ?>
	
	<section class="content">
		
		<noscript>
			<div class="alert alert-warning">
				<strong>ATENÇÃO!</strong> Para acessar este site é necessário que seu navegador tenha suporte <strong>JAVASCRIPT</strong>. 
				Para maiores informações entre em contato com o suporte técnico da sua prefeitura.
			</div>
		</noscript>
		
		<div id="msg_no_js_support" style="display:none" class="alert alert-error">
			<p><strong>ATENÇÃO! Parece que você está utilizando um navegador desatualizado ou incompatível com o SNIS</strong></p>
			<p>Para uma melhor experiência utilize um navegador atualizado. Sugerimos que você baixe  e instale a última versão de algum dos abaixo sugeridos:</p>
			<br>
			<table>
				<tr>
					<td style="width:70px; text-align:center;">
						<a href="https://www.google.com/chrome" target="_blank">
							<?= Html::img('@web/img/navegadores/chrome/chrome_32x32.png') ?>
						</a>
						<a href="https://www.google.com/chrome" target="_blank" style="color:blue">
							Google Chrome
						</a>
					</td>
					<td style="width:70px; text-align:center;">
						<a href="http://br.mozdev.org/firefox/download/" target="_blank">
							<?= Html::img('@web/img/navegadores/firefox/firefox_32x32.png') ?>
						</a>
						<a href="http://br.mozdev.org/firefox/download/" target="_blank" style="color:blue">
							Mozilla Firefox
						</a>
					</td>
					<td style="width:70px; text-align:center;">
						<a href="http://www.microsoft.com/windows/Internet-explorer/default.aspx" target="_blank">
							<?= Html::img('@web/img/navegadores/internet-explorer/internet-explorer_32x32.png') ?>
						</a>
						<a href="http://www.microsoft.com/windows/Internet-explorer/default.aspx" target="_blank" style="color:blue">
							Internet Explorer
						</a>
					</td>
					<td style="width:70px; text-align:center;">
						<a href="http://www.opera.com/pt-br/download" target="_blank">
							<?= Html::img('@web/img/navegadores/opera/opera_32x32.png') ?>
						</a>
						<a href="http://www.opera.com/pt-br/download" target="_blank" style="color:blue">
							Opera<br>&nbsp;
						</a>
					</td>
				</tr>
			</table>
		</div>
		
		
		<?= Alert::widget() ?>
			
		<?= $content ?>
			
	</section>
</div>