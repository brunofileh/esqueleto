<?php

use app\components\MenuLateralModuloWidget;

$infoModulo = $this->context->module->info;
?>

<aside class="main-sidebar">
	<section class="sidebar">
		<?php if (!Yii::$app->user->isGuest): ?>
			<?= MenuLateralModuloWidget::widget(['modulo_id' => $infoModulo['id']]) ?>
		<?php endif; ?>
	</section>
</aside>