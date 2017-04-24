<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\MenuLateralModuloWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\UsuariosPerfis */

$infoModulo = $this->context->module->info;

?>

<div class="usuarios-perfis-create">
	<h4><?= 'Perfil: ' . Html::encode( $model->tabPerfis->txt_nome ) ?></h4>
	<?=
	$this->render( '_form', [
		'model'			 => $model,
		'listaUsuarios'	 => $listaUsuarios,
		'infoModulo'	 => $infoModulo,
	] )
	?>
</div>
