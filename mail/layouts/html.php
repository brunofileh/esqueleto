<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
$title = Yii::$app->name;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= $title ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
	<table style="width: 100%" border="0">
		<tr>
			<td rowspan="3" width="60px" align="center" valign="middle">
				<img src="<?= $message->embed(Yii::getAlias('@webroot/img/layout/head_logo1.png')); ?>">
			</td>
			<td><b>Ministério das Cidades</b></td>
		</tr>
		<tr>
			<td><b>Secretaria Nacional de Saneamento Ambiental</b></td>
		</tr>
		<tr>
			<td><b><?= $title ?></td>
		</tr>
		<tr>
			<td colspan="2"><hr></td>
		</tr>
		<tr>
			<td colspan="2"><?= $content ?></td>
		</tr>
	</table>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
