<?php

use yii\helpers\Html;
use yii\helpers\Url;
use projeto\grid\GridView;
?>
<div class="row">
	<?php if (isset($msg)) { ?>
		<div class="col-md-12">
			<div class="alert-<?= $msg['tipo'] ?> alert fade in">
				<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
				<i class="icon fa fa-<?= $msg['icon'] ?>"></i>
				<?= $msg['msg'] ?>
			</div>
		</div>
	<?php } ?>
	<div class="col-lg-12">
		<?=
		GridView::widget([
			'dataProvider' => \app\modules\admin\models\TabPerfisSearch::buscaPerfisModuloGridUsuarios(),
			'columns' => [
				[
					'attribute' => 'txt_modulo',
					'label' => 'Módulo',
					'value' => 'txt_modulo',
				],
				[
					'attribute' => 'txt_perfil',
					'label' => 'Perfil',
					'value' => 'txt_perfil',
				],
				[
					'class' => 'projeto\grid\ActionColumn',
					'template' => '{delete}',
					'buttons' => [
						'delete' => function ($urls, $key, $class) {

							return Html::a('<span class="fa fa-remove"> </span>', 'javascript://;', [
									'data-toggle' => 'tooltip',
									'title' => 'Excluir',
									'data-pjax' => '0',
									'onclick' => "	
										yii.confirm('" . Yii::t('yii', 'Are you sure you want to delete this item?') . "', function (){

											projeto.ajax.post('" . Url::toRoute('/admin/perfis/exclui-perfis-modulo') . '?cod_perfil=' . $key['cod_perfil'] . "', {}, function (response) {
												var dados = $.parseJSON(response);
												$('#grid-perfil-modulos').html(dados.form);
											});
			
										}, function () {
											return false;
										});
										return false;
									"
							]);
						}
						]
					],
				]
			])
			?>
	</div>
</div>
