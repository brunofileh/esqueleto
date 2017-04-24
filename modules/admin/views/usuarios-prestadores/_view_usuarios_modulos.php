<?php

use yii\widgets\DetailView;

?>

<?=
DetailView::widget( [
	'model'		 => $model ,
	'attributes' => [
		[
			'label'	 => 'Perfil',
			'value'	 => $arrPerfis['value'],
		] ,
		[
			'label'	 => 'Funcionalidades Liberadas',
			'value'	 => implode(', ', $arrFuncionalidadesLiberadas),
		] ,						
		[
			'label'	 => 'Funcionalidades Restritas',
			'value'	 => implode(', ', $arrFuncionalidadesRestritas),
		] ,				
	] ,
] )
?>
