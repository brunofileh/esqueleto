<?php

use projeto\helpers\Html;

$link = 'http://www.snis.gov.br/downloads/manuais-atualizados/drenagem/Guia-de-Preenchimento-Aguas-Pluviais-2015.pdf';

?>

<?php $this->beginBlock('conteudo-principal') ?>
<div class="box box-default">
    <div class="box-body with-border">
		<p>
			<?= Html::a(Html::ficon('download', 'Manual de Preenchimento Águas Pluviais'), $link, [
				'target' => '_blank',
				'class' => ['btn', 'btn-default'],
			]) ?>
		</p>
	</div>
</div>
<?php $this->endBlock() ?>