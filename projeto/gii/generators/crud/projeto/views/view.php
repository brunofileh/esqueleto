<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\components\MenuLateralModuloWidget;
/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$infoModulo = $this->context->module->info;

?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view box box-default">

    <div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
        <?= "<?= " ?>Html::a('<i class="glyphicon glyphicon-pencil"></i> Editar dados', ['update', <?= $urlParams ?>], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= "<?= " ?>Html::a('<i class="glyphicon glyphicon-remove"></i> Excluir registro', ['delete', <?= $urlParams ?>], [
            'class' => 'btn btn-danger btn-sm',
            'data' => [
                'confirm' => <?= $generator->generateString('Confirma a exclusão permanente deste registro?') ?>,
                'method' => 'post',
            ],
        ]) ?>
        <?= "<?= " ?>Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Yii::$app->request->referrer, ['class' => 'btn btn-default btn-sm']) ?>
    	</div>
    </div>    
	
	<div class="box-body">
    <?= "<?= " ?>DetailView::widget([
        'model' => $model,
        'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        echo "            '" . $name . "',\n";
    }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
    }
}
?>
        ],
    ]) ?>
	</div>    
	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
		<?= "<?= " ?>Html::a('<i class="glyphicon glyphicon-pencil"></i> Editar dados', ['update', <?= $urlParams ?>], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= "<?= " ?>Html::a('<i class="glyphicon glyphicon-remove"></i> Excluir registro', ['delete', <?= $urlParams ?>], [
            'class' => 'btn btn-danger btn-sm',
            'data' => [
                'confirm' => <?= $generator->generateString('Confirma a exclusão permanente deste registro?') ?>,
                'method' => 'post',
            ],
        ]) ?>
        <?= "<?= " ?>Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Yii::$app->request->referrer, ['class' => 'btn btn-default btn-sm']) ?>
		</div>
    </div>
</div>
