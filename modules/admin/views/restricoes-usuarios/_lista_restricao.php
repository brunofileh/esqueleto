<div class="box-body">
    <?php

    use yii\bootstrap\Html;

    echo Html::activeLabel($model, 'lista_restricoes', ['class' => 'control-label']);
    echo maksyutin\duallistbox\Widget::widget([
        'model'      => $model,
        'attribute'  => 'lista_restricoes',
        'title'      => 'Funcionalidade/Ação',
        'data'       => $listaRestricoes,
        'data_id'    => 'cod_perfil_funcionalidade_acao',
        'data_value' => 'funcionalidade_acao',
        'lngOptions' => array(
            'search_placeholder' => 'Buscar Funcionalidade/Ação',
            'showing'            => 'Exibindo',
            'available'          => 'Funcionalidades/Ações disponíveis',
            'selected'           => 'Funcionalidades/Ações restringidas'
        )
    ]);
    ?>
</div>