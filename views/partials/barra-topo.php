<?php

use yii\helpers\Html;
use yii\helpers\Url;

use app\components\MenuBarraTopoModuloWidget;

?>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Navegação</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= Url::home() ?>"><?= Yii::$app->params['nome-sistema'] ?></a>
        </div>
        
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><p class="navbar-text"><?= Yii::$app->params['descr-sistema'] ?></p></li>
                <li><p class="navbar-text">&nbsp;</p></li>
                <?php if (! Yii::$app->user->isGuest): ?>
                    <li class="dropdown">
                        <a href="javascript://;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Módulos do <?= Yii::$app->params['nome-sistema'] ?> <span class="caret"></span></a>
                        <?= MenuBarraTopoModuloWidget::widget(['modulo_id' => $this->context->module->id]) ?>
                    </li>
                    <li><p class="navbar-text">&nbsp;</p></li>
                    <!-- <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Imprimir <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="dropdown-header">Relatórios</li>
                            <li><a href="#">Relatório de Inconsistências</a></li>
                            <li><a href="#">Contatos do Órgão de Manejo</a></li>
                            <li class="divider"></li>
                            <li class="dropdown-header">Formulários</li>
                            <li><a href="#">Todos os formulários</a></li>
                            <li><a href="#">Somente este formulário</a></li>
                            <li class="divider"></li>
                            <li class="dropdown-header">Glossários</li>
                            <li><a href="#">Glossário de Informações</a></li>
                            <li><a href="#">Glossário de Indicadores</a></li>
                        </ul>
                    </li> -->
                <?php endif; ?>
            </ul>
            <?php if (! Yii::$app->user->isGuest): ?>
                <form class="navbar-form navbar-left" role="search">
                    <div class="form-group">
                        <input type="text" id="busca-global" class="form-control" placeholder="Pesquisar.. ex: ER0516, RS031">
                    </div>
                </form>
            <?php endif; ?>
            
            <ul class="nav navbar-nav navbar-right">
                <?php if (! Yii::$app->user->isGuest): ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Ajuda <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><?= Html::a('Manual de Preenchimento', '#') ?></li>
                            <li><?= Html::a('Sobre o ' . Yii::$app->name, '#') ?></li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->isGuest): ?>
                    <li><?= Html::a('Entrar', Url::toRoute('/entrar')) ?></li>
                <?php else: ?>
                    <?php
                        // Nome do usuário muito comprido quebra o layout
                        $nomeUsuario = explode(' ', Yii::$app->user->identity->txt_nome)[0];
                        $altNomeUsuario = $nomeUsuario;
                        $tamMomeUsuario = Yii::$app->params['tamanho-nome-usuario-menu-global'];
                        if (mb_strlen($nomeUsuario) > $tamMomeUsuario) {
                            $nomeUsuario = mb_substr($nomeUsuario, 0, ($tamMomeUsuario -2)) . '..';
                        }
                    ?>
                    <li class="dropdown">
                        <a href="javascript://;" class="dropdown-toggle" data-toggle="dropdown" role="button" title="<?= $altNomeUsuario ?>"><?= $nomeUsuario ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><?= Html::a('Ver meu perfil', '#') ?></li>
                            <li><?= Html::a('Alterar minha senha', '#') ?></li>
                            <li><?= Html::a('Sair do sistema', Url::toRoute('/sair'), ['data-method' => 'post']) ?></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>

        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>
