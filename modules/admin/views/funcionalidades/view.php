<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabFuncionalidades */

$infoModulo = $this->context->module->info;
?>

<div class="tab-menus-view box box-default">

    <div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
			<?= Html::a( '<i class="glyphicon glyphicon-pencil"></i> Editar dados' , ['admin' , 'id' => $model->cod_funcionalidade , 'cod_modulo' => $modulo['cod_modulo']] , ['class' => 'btn btn-primary btn-sm'] ) ?>
			<?=
			Html::a( '<i class="glyphicon glyphicon-remove"></i> Excluir registro' , ['delete' , 'id' => $model->cod_funcionalidade , 'cod_modulo' => $modulo['cod_modulo']] , [
				'class'	 => 'btn btn-danger btn-sm' ,
				'data'	 => [
					'confirm'	 => 'Confirma a exclusão permanente deste registro?' ,
					'method'	 => 'post' ,
				] ,
			] )
			?>        
			<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Voltar' , Url::to( ['funcionalidades/index' , 'cod_modulo' => $modulo['cod_modulo']] ) , ['class' => 'btn btn-default btn-sm'] ) ?>
		</div>

    </div>    
	<div class="box-body">


		<?=
		DetailView::widget( [
			'model'		 => $model ,
			'attributes' => [
				'cod_funcionalidade' ,
				'txt_nome' ,
				'dsc_funcionalidade' ,
				[
					'label'	 => $model->getAttributeLabel( 'lista_menu' ) ,
					'value'	 => $model->lista_menu ,
				] ,
				[
					'label'	 => $model->getAttributeLabel( 'lista_acao' ) ,
					'value'	 => $model->lista_acao ,
				] ,
				[
					'label'	 => $model->getAttributeLabel( 'lista_perfil' ) ,
					'value'	 => $model->lista_perfil ,
				] ,
				'txt_login_inclusao:email' ,
				'dte_inclusao:date' ,
				'dte_alteracao:date' ,
			] ,
		] )
		?>
	</div>    
	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			<?= Html::a( '<i class="glyphicon glyphicon-pencil"></i> Editar dados' , ['admin' , 'id' => $model->cod_funcionalidade , 'cod_modulo' => $modulo['cod_modulo']] , ['class' => 'btn btn-primary btn-sm'] ) ?>
			<?=
			Html::a( '<i class="glyphicon glyphicon-remove"></i> Excluir registro' , ['delete' , 'id' => $model->cod_funcionalidade , 'cod_modulo' => $modulo['cod_modulo']] , [
				'class'	 => 'btn btn-danger btn-sm' ,
				'data'	 => [
					'confirm'	 => 'Confirma a exclusão permanente deste registro?' ,
					'method'	 => 'post' ,
				] ,
			] )
			?>        
			<?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i> Voltar' , Url::to( ['funcionalidades/index' , 'cod_modulo' => $modulo['cod_modulo']] ) , ['class' => 'btn btn-default btn-sm'] ) ?>

		</div>
    </div>
</div>


