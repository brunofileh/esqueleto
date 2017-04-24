<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Traduz as mensagens mais comuns de erro http
 * Essas mensagens vem do servidor e não são traduzidas pelo yii
 * @see https://github.com/yiisoft/yii2/issues/3593#issuecomment-44184959
 */
$matches = [];
$httpCodes = [
	'400' => 'Requisição inválida',
	'401' => 'Não autorizado',
	'403' => 'Acesso negado', // Proibido
	'404' => 'Não encontrado',
	'405' => 'Método não permitido',
	'408' => 'Tempo de requisição esgotou - timeout',
	'409' => 'Conflito',
	'500' => 'Erro interno do servidor',
	'501' => 'Não implementado',
	'503' => 'Serviço indisponível',
];
// Not Found (#404)
if(preg_match('/\(#([0-9]+)\)/', $name, $matches)) {
	$sentHttpCode = $matches[1];
	$name = "{$httpCodes[$sentHttpCode]} (#$sentHttpCode)";
}


$this->context->titulo = $name;

?>
<div class="site-error">
	<div class="alert alert-danger">
		<?= nl2br(Html::encode($message)) ?>
	</div>

	<?php if (YII_ENV_DEV): ?>
		<div class="alert alert-info">
			<p><strong>Informações:</strong></p>
			<p>URL = <?= $this->request->absoluteUrl ?></p>
			<?php if ($this->user->identity) : ?>
			<p>User = <?= $this->user->identity->txt_nome ?> [<?= $this->user->identity->getId() ?>]</p>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
