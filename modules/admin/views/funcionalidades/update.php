<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\MenuLateralModuloWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabFuncionalidades */

$infoModulo = $this->context->module->info;

?>

<div class="tab-funcionalidades-update">
<?php  $this->beginBlock('conteudo-principal') ?>
    <?= $this->render('_form', [
        'model' => $model,
		'modulo' => $modulo,
        'infoModulo' => $infoModulo,
	/*	'listaAcoes' => $listaAcoes,*/
		'listaPerfis' => $listaPerfis,
		'listaMenus' => $listaMenus,		
    ]) ?>
<?php  $this->endBlock() ?>
</div>
