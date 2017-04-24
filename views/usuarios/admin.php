<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\MenuLateralModuloWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Usuarios */

?>

<?php $this->beginBlock('conteudo-principal') ?>
	<div class="usuarios-admin">
		<?=  $this->render('_form', [
			'model' => $model,
		]) ?>
	</div>
<?php $this->endBlock() ?>
