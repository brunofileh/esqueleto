<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use app\components\MenuLateralModuloWidget;

$infoModulo = $this->context->module->info;
$this->params['breadcrumbs'][] = $this->context->titulo;

?>

<?= "<?php " ?> $this->beginBlock('conteudo-principal') ?>

<?= "<?php " ?>
echo yii\bootstrap\Collapse::widget( [
	'id'	 => 'box' ,
	'items'	 => [
		//DICA
		[
			'label'			 => "<i class='fa fa-info-circle'></i> Dicas de como proceder no " . $infoModulo['dsc_modulo'] ,
			'content'		 =>
			[ '1 - Cras justo odio' ,
				'2 - Dapibus ac facilisis in' ,
				'3 - Morbi leo risus' ,
				'4 - Porta ac consectetur ac' ,
				'5 - Vestibulum at eros'
			] ,
			'encode'		 => false ,
			'contentOptions' => ['class' => 'in'] ,
		// open its content by default
		] ,
		[
			'label'			 => "<i class='fa fa-info-circle'></i> Informações " ,
			'content'		 =>
			[ '1 - Cras justo odio' ,
				'2 - Dapibus ac facilisis in' ,
				'3 - Morbi leo risus' ,
				'4 - Porta ac consectetur ac' ,
				'5 - Vestibulum at eros'
			] ,
			'encode'		 => false ,
			'contentOptions' => ['class' => 'in'] ,
		// open its content by default
		] ,
	]
] );

?>
	<?= "<?php " ?>

	$info	 = $this->context->module->getInfo();
	$box	 = 1;

	if ($info['usuario-modulo']['qtd_acesso_perfil'] < 5){
	$box = 2;
	}

	$this->registerJs( "
	$('#box-collapse{$box}').removeClass('in');
	" , \yii\web\VIEW::POS_LOAD );
	
	?>
	<?= "<?php " ?>$this->endBlock() ?>




