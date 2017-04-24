<?php

use yii\helpers\Url;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\RestricoesUsuarios */

?>

<div class="restricoes-usuarios-admin">
    <?php $this->beginBlock('conteudo-principal') ?>
    <h4><?= 'Usuário: ' . Html::encode( $usuario->txt_nome ) ?></h4>

    <?=
    $this->render('_form', [
        'usuario'    => $usuario,
        'model'      => $model,
    ])
    ?>

    <?php $this->endBlock() ?>
</div>
