<?php

use yii\helpers\Html;
use yii\helpers\Url;

$mostrarLinkEntrar = true;
if ($this->context->id == 'login' && $this->context->action->id == 'index') {
	$mostrarLinkEntrar = false;
}

?>

