<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Modulos */

$infoModulo = $this->context->module->info;

?>

<div class="modulos-admin">

	<?=
	$this->render( '_form' , [
		'model'		 => $model ,
		'infoModulo' => $infoModulo ,
	] );
	?>


</div>
