<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\components\MenuLateralModuloWidget;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $model app\models\RlcModulosPrestadores */
?>

<?=
DetailView::widget([
	'model'		 => $model,
	'attributes' => [
		'txt_secretaria_nome',
		'tabMunicipios.sgl_estado_fk',
		'tabMunicipios.txt_nome',
		'num_secretaria_cep',
		'txt_secretaria_endereco',
		'txt_secretaria_complemento',
		'num_secretaria_numero',
		'txt_secretaria_bairro',
		'txt_secretaria_site',
		'txt_secretaria_email:email',
		'txt_secretaria_email2:email',
		'num_secretaria_fone',
		'num_secretaria_fone2',
		'num_secretaria_fone3',
		'num_secretaria_fax',
		'dte_inclusao:date',
		'txt_observacoes'
	],
]);
?>

<h3>Responsáveis</h3>

<?=
TabsX::widget([
	'items'			 => [
		[
			'label'			 => 'Encarregado',
			'headerOptions'	 => ['id' => $model->tabModulos->id . '_encarregado'],
			'content'		 => $this->render('_view_contatos', ['model' => $model, 'tipo' => 'encarregado']),
		],
		[
			'label'			 => 'Outros',
			'headerOptions'	 => ['id' => $model->tabModulos->id . '_outros'],
			'content'		 => $this->render('_view_contatos', ['model' => $model, 'tipo' => 'outro']),
		]
	],
	'position'		 => TabsX::POS_LEFT,
	'bordered'		 => true,
	'encodeLabels'	 => false,
	'options'		 => ['id' => 'contatos_modulos_' . $model->tabModulos->id],
]);
?>

