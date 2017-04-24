<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\MenuLateralModuloWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Usuarios */

$infoModulo = $this->context->module->info;

?>

<div class="usuarios-admin">
<?php  $this->beginBlock('conteudo-principal') ?>
 
    <?= $this->render('_form', [
        'model' => $model,
		'modelTabUsuariosSearch' => $modelTabUsuariosSearch,
		'listaPrestadores' => $listaPrestadores,
		'arrModulos' => $arrModulos,
		'arrPerfis' => $arrPerfis,
		'arrFuncionalidades' => $arrFuncionalidades,		
        'infoModulo' => $infoModulo,
    ]) ?>
	
<?php  $this->endBlock() ?>
</div>
