<?php

use app\components\MenuLateralModuloWidget;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<aside class="main-sidebar">

	<section class="sidebar">

		<?php if (!Yii::$app->user->isGuest): ?>
			<?php /* ?>
			<!-- search form -->
			<form action="#" method="get" class="sidebar-form">
				<div class="input-group">
					<input type="text" name="q" class="form-control" placeholder="Buscar..."/>
					<span class="input-group-btn">
						<button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
						</button>
					</span>
				</div>
			</form>
			<!-- /.search form -->
			<?php */ ?>
			<?= dmstr\widgets\Menu::widget([
				'options' => ['class' => 'sidebar-menu'],
				'items' => [
					['label' => 'Início', 'icon' => 'glyphicon glyphicon-home', 'url' => [ '/home']],
					['label' => 'Minha Conta', 'icon' => 'glyphicon glyphicon-user', 'url' => ['/usuarios/view']],
					['label' => 'Alterar senha', 'icon' => 'glyphicon glyphicon-exclamation-sign', 'url' => ['/alterar-senha']],
					[
						'label'	=> 'Sair',
						'icon' => 'glyphicon glyphicon-off',
						'url' => Url::toRoute('/sair'),
						'template' => '<a href="{url}" data-method="post">{icon}{label}</a>'
					]],
				])
            ?>
		<?php endif; ?>
	</section>
</aside>
