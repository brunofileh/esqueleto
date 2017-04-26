<?php


/* @var $this yii\web\View */
/* @var $model app\models\ModeloDocs */

$infoModulo = $this->context->module->info;

?>

<div class="modelo-docs-create">

    <?= $this->render('_form', [
        'model' => $model,
        'infoModulo' => $infoModulo,
    ]) ?>
	
</div>
