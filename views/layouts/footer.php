<?php

use projeto\helpers\Html;
use yii\helpers\Url;
use app\models\VisAtributosValores;

$arrCodUsr = explode('|', VisAtributosValores::getDescrOpcao(
	VisAtributosValores::getTupla('cod-usuario-problema-javascript','cod-usuario'))[0]
);

?>

<?php
$m = app\modules\admin\models\TabModulosSearch::getEquipeModuloId(\Yii::$app->controller->module->id);
if ($m) {
    echo "<footer class='main-footer'>";
        echo "<b>{$m['txt_equipe']}</b><br/>";
        echo "<i class='fa fa-envelope'></i> " . Html::a($m['txt_email_equipe'], 'mailto:' . $m['txt_email_equipe'] . '') . "&nbsp&nbsp&nbsp";
        echo Html::icon('phone-alt', $m['num_fones_equipe']);
    echo "</footer>";
}
?>

<?php if (!in_array($this->user->id, $arrCodUsr)): ?>
	<?= \mgcode\sessionWarning\widgets\SessionWarningWidget::widget([
		'logoutUrl' => Url::to(['/sair']), //  if is set, logout button will be shown before Continue button. Default: null
		'extendUrl' => Url::to(['/session-warning/extend']), // url where ajax request is sent, when continue button is clicked. Default: ['/session-warning/extend']
		'warnBefore' => 300, // time in seconds before user is warned about expiring session. Default: 300 (5min)
	]); ?>
<?php endif ?>