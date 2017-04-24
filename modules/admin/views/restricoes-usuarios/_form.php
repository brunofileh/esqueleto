<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TabRestricoesUsuarios */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="tab-restricoes-usuarios-form box box-default">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box-header with-border">
        <h3 class="box-title"></h3>
        <div class="box-tools">
            <?php 
            if ($model->podeVincular){
                echo Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')]);
            }
            ?>
            <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Cancelar', Url::toRoute('/admin/usuarios'), ['class' => 'btn btn-default btn-sm']) ?>
        </div>

    </div>

    <div class="box-body">
        <!-- LISTA DE PERFIS -->
        <?php if ($model->podeVincular): ?>
            <?php
            $itens = ArrayHelper::map($usuario->rlcUsuariosPerfis, 'tabPerfis.cod_perfil', function($model)
                {
                    return $model->tabPerfis->tabModulos->dsc_modulo . ' - ' . $model->tabPerfis->dsc_perfil;
                });
            ?>

            <?php
            echo $form->field($model, 'modulo_perfil')
                ->dropDownList($itens, [
                    'prompt'   => $this->app->params['txt-prompt-select'],
                    'onchange' => '
                    $.get( "' . Url::toRoute('restricoes-usuarios/listar-funcionalidades') . '", {
                            cod_perfil_fk: $(this).val(), cod_usuario_fk: ' . $usuario->cod_usuario . ' 
                        } )
                        .done(function( data ) {
                            $("#dlb-lista_restricoes").html(data);
                        }
                    );'
            ]);
            ?>

            <br />
            
            <div id="dlb-lista_restricoes"></div>

            <?php
        endif;
        ?>
    </div>

    <div class="box-footer">
        <h3 class="box-title"></h3>
        <div class="box-tools pull-right">
            <?php 
            if ($model->podeVincular){
                echo Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Incluir registro' : 'Alterar registro'), ['class' => ($model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm')]);
            }
            ?>
            <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Cancelar', Url::toRoute('/admin/usuarios'), ['class' => 'btn btn-default btn-sm']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
