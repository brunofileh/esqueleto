<?php 

use projeto\helpers\Html;
/*
$path = Yii::getAlias('@webroot/img/logo-snis.png');
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
$img = '<img src="'. $base64 .'" width="70" height="56">';
*/
//die(\yii\helpers\Url::to('@web/img/logo-snis.png', true));
?>

<table class="table" cellpadding="0" cellspacing="0">
	<tr>
		<td class="text-center">
			<?= Html::img(\yii\helpers\Url::to('@web/img/logo-snis.png', true), ['width' => '70', 'height' => '56']) ?>
		</td>
		<td class="text-center">
			<h3><?= $titulo ?></h3>
			<h4><?= $subtitulo ?></h4>
			<h4><?= $subtitulo2 ?></h4>
		</td>
		<td class="text-center">
			<h2><?= $ano ?></h2>
		</td>
	</tr>
</table>