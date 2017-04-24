<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Perfis */

$infoModulo = $this->context->module->info;

?>

<div class="perfis-admin">

    <?= $this->render('_form', [
        'model' => $model,
		 'modulo' => $modulo,
        'infoModulo' => $infoModulo,
    ]) ?>
	
</div>
