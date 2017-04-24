<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\MenuLateralModuloWidget;

/* @var $this yii\web\View */
/* @var $model app\models\AtributosValores */

if (!isset(Yii::$app->request->queryParams['TabAtributosValoresSearch']['fk_atributos_valores_atributos_id'])) {
	throw new yii\web\NotFoundHttpException('Página não encontrada');
}

$infoModulo = $this->context->module->info;
$fk_atributos_valores_atributos_id = Yii::$app->request->queryParams['TabAtributosValoresSearch']['fk_atributos_valores_atributos_id'];
$linkBack = Url::toRoute(['index', 'TabAtributosValoresSearch[fk_atributos_valores_atributos_id]' => $fk_atributos_valores_atributos_id]);
$model->fk_atributos_valores_atributos_id = $fk_atributos_valores_atributos_id;

?>

<div class="atributos-valores-create">
<?php  $this->beginBlock('conteudo-principal') ?>

    <?= $this->render('_form', [
        'model' => $model,
        'infoModulo' => $infoModulo,
        'linkBack' => $linkBack,
    ]) ?>
	
<?php  $this->endBlock() ?>
</div>
