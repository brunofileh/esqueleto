<?php

use yii\helpers\Url;
use projeto\helpers\Html;
use app\models\RlcModulosPrestadoresSearch;
use app\models\TabAtributosValoresSearch;

$controller = \Yii::$app->controller;
$module = $controller->module;
$infoModule = $module->info;

$url = \Yii::$app->urlManager->createUrl("/{$module->id}/{$controller->id}/selecionar-ano-ref");

/**
 * seleciona os anos que o prestador participou em coletas
 */
if ($module->id != 'admin') {
	$part		= \Yii::$app->controller->getDirTabPartipacoesSearchModulo();
	$tbPart		= $part::tableName();
	$tbModPrest	= RlcModulosPrestadoresSearch::tableName();

	$anosParticipacoes = [];
	$anosParticipacoesTmp = $part::find()
		->distinct(true)
		->select([
			"{$tbPart}.cod_participacao",
			"{$tbPart}.ano_ref",
		])
		->innerJoin($tbModPrest, "{$tbModPrest}.cod_modulo_prestador = {$tbPart}.cod_modulo_prestador_fk")
		->where([
			"{$tbModPrest}.cod_prestador_fk" => $this->session->get('TabParticipacoes')['cod_prestador']
		])
		->orderBy("{$tbPart}.ano_ref DESC")
		->asArray()
		->all()
	;

	$arrSitAnoRefBloqueado = [
		TabAtributosValoresSearch::NAO_INICIADO,
		TabAtributosValoresSearch::SENDO_REALIZADO_PELO_PRESTADOR,
		TabAtributosValoresSearch::SENDO_REALIZADO_INTERNAMENTE,
	];

	$codSitPre = $this->session->get('TabParticipacoes')['situacao_preenchimento']['cod_situacao_preenchimento'];
	$anoRefBloqueado = false;
	if (in_array($codSitPre, $arrSitAnoRefBloqueado)) {
		$anoRefBloqueado = true;
	}

	$tabParticipacoes = $this->session->get('anoRefSelecionado');
	if (isset($tabParticipacoes['formularios'])) {
		foreach ($anosParticipacoesTmp as $item) {
			if($tabParticipacoes['formularios']['ano_ref'] == $item['ano_ref']) {
				$anoRefAtual = $item;
			}
			else {
				$anosParticipacoes[]= $item;
			}
		}
	}
	else {
		if (empty($anosParticipacoes)) {
			$anoRefAtual = ['ano_ref' => $this->app->params['ano-ref']];
		}
		else {
			$anoRefAtual = array_shift($anosParticipacoes);
		}
	}

	if ($anoRefBloqueado) {
		$anosParticipacoes = [];
	}
}
else {
	$anosParticipacoes = [];
}

/**
 * END - seleciona os anos que o prestador participou em coletas
 */

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
		
	<?php if (count($anosParticipacoes) > 0): ?>
		<li class="dropdown">
			<a href="javascript://;" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?= $anoRefAtual['ano_ref'] ?> <span class="caret"></span></a>
			<ul class="dropdown-menu" role="menu">
				<?php foreach ($anosParticipacoes as $ano): ?>
					<li>
						<a href="javascript://;" onclick="selecionarAnoRef('<?= $ano['cod_participacao'] ?>', '<?= $ano['ano_ref'] ?>', '');"><i class="menu-icon"></i> <?= $ano['ano_ref'] ?></a>
					</li>
				<?php endforeach ?>
			</ul>
		</li>
	<?php elseif (isset($anoRefAtual)): ?>
		<li>
			<p class="navbar-text" style="color:#fff"><i class="fa fa-calendar"> </i> <b><?= $anoRefAtual['ano_ref'] ?></b></p>
		</li>
	<?php endif ?>
</ul>

<?= Html::hiddenInput('urlSelecionarAnoRef', $url) ?>