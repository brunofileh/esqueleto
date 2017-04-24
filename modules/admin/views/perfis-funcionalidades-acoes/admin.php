<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\MenuLateralModuloWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\PerfisFuncionalidadesAcoes */

$infoModulo = $this->context->module->info;

?>

<div class="perfis-funcionalidades-acoes-admin">
<?php  $this->beginBlock('conteudo-principal') ?>
 
    <?= $this->render('_form', compact( 'model', 'listaAcoes', 'listaPerfis', 'infoModulo', 'listaAcao', 'cod_modulo')  ) ?>
	
<?php  $this->endBlock() ?>
</div>
