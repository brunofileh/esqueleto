<?php

$infoModulo = $this->context->module->info;

?>

<div class="atualiza-populacao">
<?php  $this->beginBlock('conteudo-principal') ?>

    <?= $this->render('_form_coleta-snis', [
        'model' => $model,
        'infoModulo' => $infoModulo,
		'importacao'=>$importacao 
    ]) ?>
	
<?php  $this->endBlock() ?>
</div>
