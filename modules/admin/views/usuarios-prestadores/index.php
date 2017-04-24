<?php
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\TabUsuariosPrestadoresSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\components\MenuLateralModuloWidget;
use app\modules\admin\models\TabUsuariosPrestadoresSearch;
use app\models\TabPrestadoresSearch;
use app\models\TabAtributosValoresSearch;
use app\models\TabAtributosSearch;
use yii\widgets\MaskedInput;

$infoModulo = $this->context->module->info;

?>

<?php  $this->beginBlock('conteudo-principal') ?>
<div class="usuarios-index box box-default">

	<div class="box-header with-border">
		<h3 class="box-title">Consulta</h3>
		<div class="box-tools pull-right">
			 <?= Html::a('<i class="glyphicon glyphicon-plus"></i> Incluir novo registro', ['admin'], ['class' => 'btn btn-success btn-sm']) ?>
		</div>
	</div>

	<div class="box-body with-border">

		<?php
			$filterP = TabPrestadoresSearch::getPrestadoresComUsuarios($codPrestadorFk);
			if ($codPrestadorFk) {
				$filterP = false;
			}
		
		?>
			
	    <?= GridView::widget([
	        'dataProvider' => $dataProvider,
	        'filterModel' => $searchModel,
	        'columns' => [
	            'txt_nome',			
				[
	                'attribute'  => 'num_fone',
	                'filter'     => MaskedInput::widget([
	                    'name'   => 'TabUsuariosPrestadoresSearch[num_fone]',
	                    'value'  => $searchModel->num_fone,
	                    'mask'   => ['(99) 9999-9999', '(99) 99999-9999'],
	                ]),
	            ],
	            'txt_email:email',
				[
					'attribute'	 => 'txt_ativo' ,
					'content'	 => function($data){
						return TabAtributosValoresSearch::getDescricaoAtributoValor(TabAtributosSearch::OPT_SIM_NAO, $data->txt_ativo );
					} ,
					'contentOptions' => ['style' => 'width: 100px'] ,
					'filter'		 => TabUsuariosPrestadoresSearch::getSimNao($codPrestadorFk),
				] ,			
				[
					'attribute'	 => 'cod_prestador_fk' ,
					'content'	 => function($data){
						return TabPrestadoresSearch::getDescricaoPrestador($data->cod_prestador_fk);
					} ,
					'contentOptions' => ['style' => 'width: 300px'] ,
					'filter'		 => $filterP,
				] ,								            
	            [
	            	'class'		 => 'projeto\grid\ActionColumn',
					'template'	 => '{view} {update} {delete}',
					'buttons'	 => [
						'update' => function ($action , $model , $key){
							return Html::a('<span class="fa fa-edit"></span>' , Url::to( ['usuarios-prestadores/admin' , 'id' => $model->cod_usuario] ) , [
								'title' => 'Alterar',
								'data-toggle' => 'tooltip',
							]);
						},
					],
				],
			],
		]) ?>
	</div>
	
	<div class="box-footer">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			 <?= Html::a('<i class="glyphicon glyphicon-plus"></i> Incluir novo registro', ['admin'], ['class' => 'btn btn-success btn-sm']) ?>
		</div>
	</div>
	
</div>
<?php  $this->endBlock() ?>
