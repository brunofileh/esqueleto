<?php

use yii\helpers\Html;
use yii\helpers\Url;

dmstr\web\AdminLteAsset::register($this);

if (class_exists('backend\assets\AppAsset')) {
    backend\assets\AppAsset::register($this);
} else {
    app\assets\ProjetoAsset::register($this);
}

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

if ($this->session->hasAlert()) {
    $this->session->makeAlert($this->session->getAlert(), $this);
}
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode(Yii::$app->params['nome-sistema'] . ' - ' . Yii::$app->params['descr-sistema']) ?></title>

        <?= Html::tag('base', null, ['href' => Url::home()]) ?>
        <?=
        Html::tag('link', null, [
            'href' => Url::home() . 'favicon.ico',
            'rel' => 'shortcut icon',
            'type' => 'image/vnd.microsoft.icon'
        ])
        ?>
    <?php $this->head() ?>
    </head>
    <?php
    $bodyClass = ['fixed', 'sidebar-mini', 'layout-boxed'];
    if (Yii::$app->user->isGuest || (isset($this->context->module->info))) {
        if (isset($this->context->module->info)) {
            $bodyClass[] = $this->context->module->info['txt_tema'];
        }
    } else {
        $bodyClass[] = 'skin-yellow-light';
    }
    if (isset($_COOKIE['menu-aberto']) && $_COOKIE['menu-aberto'] == '0') {
        $bodyClass[] = 'sidebar-collapse';
    }
    ?>
    <body class="<?= implode(' ', $bodyClass) ?>">
<?php $this->beginBody() ?>

        <div class="wrapper">
            <?php
            echo $this->render('header.php', ['directoryAsset' => $directoryAsset]);
            if (!Yii::$app->user->isGuest) {
                $left = isset($this->context->module->info) ? 'left.php' : 'left2.php';
                echo $this->render($left, ['directoryAsset' => $directoryAsset]);
            }
            ?>
            <?= $this->render('content.php', ['content' => $content, 'directoryAsset' => $directoryAsset]) ?>
            <?= $this->render('right.php', ['directoryAsset' => $directoryAsset]) ?>
        <?= $this->render('footer.php', ['directoryAsset' => $directoryAsset]) ?>
        </div>
        <?php $this->endBody() ?>

<?php /** BOOT JS * */ ?>
        <script type="text/javascript">
            if (typeof jQuery == 'undefined') {
                document.getElementById('msg_no_js_support').style.display = 'block';
            }

            jQuery(document).ready(function () {
                /**
                 * Esquema para abrir e fechar o menu
                 * @todo colocar como opção no perfil do usuário?
                 */
                jQuery('a.sidebar-toggle').click(function () {
                    Cookies.set('menu-aberto', 0 + jQuery('body').hasClass('sidebar-collapse'));
                });
            });
<?php if (YII_ENV == 'prod'): ?>
                (function (i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r;
                    i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                    a = s.createElement(o),
                            m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

                ga('create', 'UA-41806374-6', 'auto');
                ga('send', 'pageview');
<?php endif ?>
        </script>
    </body>
</html>
<?php $this->endPage() ?>
