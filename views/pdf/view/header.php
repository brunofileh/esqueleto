<?php 

use projeto\helpers\Html;
use kartik\mpdf\Pdf;

?>

<table class="table">
	<tr>
		<td class="text-center">
			<?= Html::img('@web/img/logo-snis-header-pdf.png') ?>
		</td>
		<td class="text-center">
			<h4><?= $titulo ?></h4>
			<h5><?= $subtitulo ?></h5>
			<?php if (isset($subtitulo2)): ?>
				<h5><?= $subtitulo2 ?></h5>
			<?php endif ?>
		</td>
		<td class="text-center">
			<h3><?= $ano ?></h3>
		</td>
	</tr>
</table>
