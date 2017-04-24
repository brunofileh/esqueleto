<?php

use yii\helpers\Html;
use yii\helpers\Url;

dmstr\web\AdminLteAsset::register($this);
app\assets\ProjetoAsset::register($this);

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode(Yii::$app->params['nome-sistema'] . ' - ' . Yii::$app->params['descr-sistema']) ?></title>

	<?= Html::tag('base', null, ['href' => Url::home()]) ?>
	<?= Html::tag('link', null, [
		'href'	 => Url::home() . 'favicon.ico',
		'rel'	 => 'shortcut icon',
		'type'	 => 'image/vnd.microsoft.icon'
	]) ?>
	<?php $this->head() ?>
</head>
<body class="layout-top-nav">
	<?php $this->beginBody() ?>
		<div style="background-color: #195128;background-image: radial-gradient(ellipse at center,#17882c 1%,#00510f 100%);">
			<div class="container-fluid" style="padding-bottom: 7px; padding-top: 7px;">
				<div class="row">
					<div class="col-md-2" style="padding-top: 12px;">
						<?= Html::img('@web/img/snis.png') ?>
					</div>
					<div class="col-md-10" style="margin-left: -40px;">
						<a>
							<span style="color: #FFF; display: block; position: relative; top: 10px;">Ministério das Cidades</span>
                            <span style="color: #FFF; font-size: 3.0em; font-family: open_sansbold,'Open Sans',Arial,Helvetica,sans-serif;"><?= $this->app->params['nome-sistema'] ?><span style="color: #FF0000;"><?= (YII_ENV_PROD ? null : "-" . YII_ENV) ?></span></span>
							<span style="color: #FFF; display: block; font-size: 1.2em; position: relative; top: -10px;">SISTEMA NACIONAL DE INFORMAÇÕES SOBRE SANEAMENTO</span>
						</a>
						<div style="color: #FFF">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div style="background-color: #00420c; height: 35px;">&nbsp;</div>
			
		<div class="wrapper">
			<?= $this->render('header-publico.php', ['directoryAsset' => $directoryAsset]) ?>
			<?= $this->render('content.php', ['content' => $content]) ?>
		</div>
		
		<div style="background-color: #00420c; height: 60px;">&nbsp;</div>
		
		<div style="background-color: #002907; padding: 35px 0">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6">
						<?= Html::img('@web/img/acesso-a-informacao.png') ?>
					</div>
					<div class="col-md-6 text-right">
						<?= Html::img('@web/img/brasil.png') ?>
					</div>
				</div>
			</div>
		</div>
	<?php $this->endBody() ?>
	
<style type="text/css">
.container-fluid {
  margin-right: auto;
  margin-left: auto;
  max-width: 950px; /* or 950px */
}
div.content-wrapper {
   min-height: 100px; 
}
</style>

<script type="text/javascript">
$(function () {
	$('div.content-wrapper').css('min-height', '500px')
});
</script>

</body>
</html>
<?php $this->endPage() ?>
