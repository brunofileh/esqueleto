<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\components\MenuLateralModuloWidget;
use kartik\tabs\TabsX;
use app\models\TabPrestadoresSearch;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabUsuarios */

$infoModulo = $this->context->module->info;

?>

<?php  $this->beginBlock('conteudo-principal') ?>
<div class="tab-usuarios-view box box-default">

    <div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools">
        <?= Html::a('<i class="glyphicon glyphicon-pencil"></i> Editar dados', ['admin', 'id' => $model->cod_usuario], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<i class="glyphicon glyphicon-remove"></i> Excluir registro', ['delete', 'id' => $model->cod_usuario], [
            'class' => 'btn btn-danger btn-sm',
            'data' => [
                'confirm' => 'Confirma a exclusão permanente deste registro?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Url::toRoute($infoModulo['menu-item']['txt_url']), ['class' => 'btn btn-default btn-sm']) ?>
    	</div>
    </div>    
	
	<div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
			[
				'label'	 => $model->getAttributeLabel('cod_prestador_fk'),
				'value'	 => TabPrestadoresSearch::getDescricaoPrestador($model->cod_prestador_fk),
			],			
            'txt_nome',
			'num_fone',
            'txt_email:email', 
			[
				'label'	 => $model->getAttributeLabel('txt_ativo'),
				'value'	 => $model->getDescricaoSimNao('txt_ativo'),
			],
            'txt_login_inclusao:email',
            'dte_inclusao:date',
            'dte_alteracao:date',
        ],
    ]) ?>
	
	<?php
		echo "<div class='help-block'></div>";
		
		$active = true;
		foreach ($arrModulos as $key => $value) {
			if (!isset($arrFuncionalidadesRestritas[$value['cod_modulo']])) {
				$arrFuncionalidadesRestritas[$value['cod_modulo']][] = 'Todas sem restrições';
			}
			if (!isset($arrFuncionalidadesLiberadas[$value['cod_modulo']])) {
				$arrFuncionalidadesLiberadas[$value['cod_modulo']][] = 'Todas com restrições';
			}			
			if (!isset($arrPerfis[$value['cod_modulo']])) {
				$arrPerfis[$value['cod_modulo']]['value'] = 'Sem perfil';
			}			
			$itens[] = 
				[
					'label'   => $value['nome_modulo'],
					'content' => $this->render('_view_usuarios_modulos', [						
						'model' => $model,
						'arrPerfis' => $arrPerfis[$value['cod_modulo']],						
						'arrFuncionalidadesRestritas' => $arrFuncionalidadesRestritas[$value['cod_modulo']],
						'arrFuncionalidadesLiberadas' => $arrFuncionalidadesLiberadas[$value['cod_modulo']],
					]),													
					'active'  => $active
				]
			;
			$active = false;
		}
		
		echo TabsX::widget([
			'items'        => $itens,
			'position'     => TabsX::POS_ABOVE,
			'bordered'     => true,
			'encodeLabels' => false
		]);		
	?>
	</div>    
	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
		<?= Html::a('<i class="glyphicon glyphicon-pencil"></i> Editar dados', ['admin', 'id' => $model->cod_usuario], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<i class="glyphicon glyphicon-remove"></i> Excluir registro', ['delete', 'id' => $model->cod_usuario], [
            'class' => 'btn btn-danger btn-sm',
            'data' => [
                'confirm' => 'Confirma a exclusão permanente deste registro?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Url::toRoute($infoModulo['menu-item']['txt_url']), ['class' => 'btn btn-default btn-sm']) ?>
		</div>
    </div>
</div>
<?php  $this->endBlock() ?>
