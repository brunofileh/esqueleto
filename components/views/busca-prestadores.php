<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use projeto\helpers\Html;

?>

<?= $this->render('@app/views/consulta-prestadores/_view_consulta', [
	'model' => $model,
	'cod_modulo' => $cod_modulo,
	'pageSize' => $pageSize,
	'action' => $action,
	'arrConfigCampos' => $arrConfigCampos,
]) ?>

<?= $this->render('@app/views/consulta-prestadores/_view_resultado', [
    'model' => $model,
    'dataProvider' => $dataProvider,
    'action' => $action,
	'nomeAcao' => $nomeAcao,
]) ?>
