<?php

use yii\helpers\Url;
use projeto\helpers\Html;
use app\models\RlcModulosPrestadoresSearch;
use app\models\TabAtributosValoresSearch;

$controller = \Yii::$app->controller;
$module = $controller->module;
$infoModule = $module->info;

$url = \Yii::$app->urlManager->createUrl("/{$module->id}/{$controller->id}/selecionar-ano-ref");


?>

<ul class="nav navbar-nav">
	<?php if(count($modulos) > 1): ?>
		<li class="dropdown">
			<a href="<?= Url::toRoute($infoModule['txt_url']) ?>" class="dropdown-toggle titulo-modulo" data-toggle="dropdown" aria-expanded="false"><?= $infoModule['txt_nome'] ?> <span class="caret"></span></a>
			<ul class="dropdown-menu" role="menu">
				<?php foreach ($modulos as $module): ?>
					<?php if($module['cod_modulo_fk'] !== $infoModule['cod_modulo']): ?>
						<li><a href="<?= Url::to([$module['modulo_url']]) ?>" title="<?= $module['dsc_modulo'] ?>"><?= $module['nome_modulo'] ?></a></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</li>
	<?php else: ?>
		<li>
			<a href="<?= Url::toRoute($infoModule['txt_url']) ?>" title="<?= $infoModule['dsc_modulo'] ?>" class="titulo-modulo"><?= $infoModule['txt_nome'] ?></a>
		</li>
	<?php endif; ?>
		
	
</ul>

<?= Html::hiddenInput('urlSelecionarAnoRef', $url) ?>