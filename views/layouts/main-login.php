<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

dmstr\web\AdminLteAsset::register($this);

app\assets\ProjetoAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?= Html::tag('link', null, [
			'href'	 => Url::home() . 'favicon.ico',
			'rel'	 => 'shortcut icon',
			'type'	 => 'image/vnd.microsoft.icon'
		]) ?>
    <?php $this->head() ?>
</head>
<body class="hold-transition pagina-login">
	<?php $this->beginBody() ?>
		<?= $content ?>
	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
