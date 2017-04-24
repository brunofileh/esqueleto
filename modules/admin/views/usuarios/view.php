<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabUsuarios */

$infoModulo = $this->context->module->info;

?>


<div class="tab-modulos-view  box box-default">

    <div class="box-header with-border">
        <h3 class="box-title"></h3>
        <div class="box-tools">
            <?= Html::a('<i class="glyphicon glyphicon-pencil"></i> Editar dados', ['admin', 'id' => $model->cod_usuario], ['class' => 'btn btn-primary btn-sm']) ?>
            <?php if ($this->context->module->getInfo()['usuario-perfil']['cod_usuario_fk'] != $model->cod_usuario) { ?>
            <?=
            Html::a('<i class="glyphicon glyphicon-remove"></i> Excluir registro', ['delete', 'id' => $model->cod_usuario], [
                'class' => 'btn btn-danger btn-sm',
                'data'  => [
                    'confirm' => 'Confirma a exclusão permanente deste registro?',
                    'method'  => 'post',
                ],
            ])
            ?>
            <?php } ?>
            <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Url::toRoute($infoModulo['menu-item']['txt_url']), ['class' => 'btn btn-default btn-sm']) ?>
        </div>
    </div>

    <div class="box-body">
        <?=
        DetailView::widget([
            'model'      => $model,
            'attributes' => [
					[
						'attribute'=>'txt_imagem',
						'value'=> $model->txt_imagem,
						'format' => ['image',['width'=>'100','height'=>'100']],
					],
                'txt_nome',
				'txt_login',
                'txt_email:email',
				'num_cpf',
                'num_fone',
                'qtd_acesso',
                'txt_ativo:boolean',
				[
					'label'	 => 'Módulo(s)',
					'value'	 => $model->lista_modulos,
				],
                'dte_inclusao:date',

            ],
        ])
        ?>
    </div>

    <div class="box-footer">
        <h3 class="box-title"></h3>
        <div class="box-tools pull-right">
            <?= Html::a('<i class="glyphicon glyphicon-pencil"></i> Editar dados', ['admin', 'id' => $model->cod_usuario], ['class' => 'btn btn-primary btn-sm']) ?>
            <?php if ($this->context->module->getInfo()['usuario-perfil']['cod_usuario_fk'] != $model->cod_usuario) { ?>
            <?=
            Html::a('<i class="glyphicon glyphicon-remove"></i> Excluir registro', ['delete', 'id' => $model->cod_usuario], [
                'class' => 'btn btn-danger btn-sm',
                'data'  => [
                    'confirm' => 'Confirma a exclusão permanente deste registro?',
                    'method'  => 'post',
                ],
            ])
            ?>
            <?php } ?>
            <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', Url::toRoute($infoModulo['menu-item']['txt_url']), ['class' => 'btn btn-default btn-sm']) ?>
        </div>
    </div>
</div>
