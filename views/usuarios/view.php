<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabUsuarios */

$infoModulo = [];
?>

<div class="tab-modulos-view  box box-default">

    <div class="box-header with-border">
        <h3 class="box-title"></h3>
        <div class="box-tools">
			<?= Html::a('<i class="glyphicon glyphicon-lock"></i> Alterar Senha', ['/alterar-senha'], ['class' => 'btn btn-primary btn-sm']) ?>
			<?= Html::a('<i class="glyphicon glyphicon-pencil"></i> Editar dados do usuário', ['admin'], ['class' => 'btn btn-primary btn-sm']) ?>
			<?php /*
			if (Yii::$app->user->identity->txt_tipo_login == 2 && Yii::$app->user->identity->cod_prestador_fk) {
				echo Html::a('<i class="fa fa-university"></i> Editar dados da prefeitura', ['/gestao/prefeituras/view', 'id' => Yii::$app->user->identity->cod_prestador_fk], ['class' => 'btn btn-primary btn-sm']);
			}*/
			?>

        </div>
    </div>

    <div class="box-body">
		<?=
		DetailView::widget([
			'model'		 => $model,
			'attributes' => [
				[
					'attribute'	 => 'txt_imagem',
					'value'		 => $model->txt_imagem,
					'format'	 => ['image', ['width' => '100', 'height' => '100']],
				],
				'num_cpf',
				'txt_login',
				'txt_nome',
				'txt_email:email',
				'num_fone',
				'dte_inclusao:date',
				'txt_ativo:boolean',
				[
					'label'	 => 'Módulo(s)',
					'value'	 => $model->lista_modulos,
				],
				'qtd_acesso',
			],
		])
		?>
    </div>

    <div class="box-footer">
        <h3 class="box-title"></h3>
        <div class="box-tools pull-right">
			<?= Html::a('<i class="glyphicon glyphicon-lock"></i> Alterar Senha', ['/alterar-senha'], ['class' => 'btn btn-primary btn-sm']) ?>
			<?= Html::a('<i class="glyphicon glyphicon-pencil"></i> Editar dados do usuário', ['admin'], ['class' => 'btn btn-primary btn-sm']) ?>         
			<?php /*
			if (Yii::$app->user->identity->txt_tipo_login == 2 && Yii::$app->user->identity->cod_prestador_fk) {
				echo Html::a('<i class="fa fa-university"></i> Editar dados da prefeitura', ['/gestao/prefeituras/view', 'id' => Yii::$app->user->identity->cod_prestador_fk], ['class' => 'btn btn-primary btn-sm']);
			}*/
			?>
        </div>
    </div>
</div>
