<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\MenuLateralModuloWidget;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelTituloClass, '\\') ?> */

$infoModulo = $this->context->module->info;

?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelTituloClass)) ?>-admin">
<?= "<?php " ?> $this->beginBlock('conteudo-principal') ?>
 
    <?= "<?= " ?>$this->render('_form', [
        'model' => $model,
        'infoModulo' => $infoModulo,
    ]) ?>
	
<?= "<?php " ?> $this->endBlock() ?>
</div>
