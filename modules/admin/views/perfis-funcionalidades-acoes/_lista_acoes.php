<div class="box-body">
    <?php

    use yii\bootstrap\Html;

    echo maksyutin\duallistbox\Widget::widget([
        'model'      => $model,
        'attribute'  => 'lista_acao',
        'title'      => 'Ação',
        'data'       => $listaAcao,
        'data_id'    => 'cod_acao',
        'data_value' => 'dsc_acao',
        'lngOptions' => array(
            'search_placeholder' => 'Perquisar por ações',
            'showing'            => 'Exibindo',
            'available'          => 'Ações disponíveis',
            'selected'           => 'Ações selecionadas'
        )
    ]);
    ?>
</div>