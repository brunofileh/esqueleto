<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\MenuBarraTopoModuloWidget;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
	<!-- Create the tabs -->
	<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
		<li class="active"><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-question-circle"></i></a></li>
	</ul>
	<!-- Tab panes -->

	<div class="tab-content">
		<!-- Settings tab content -->
		<div class="tab-pane active" id="control-sidebar-settings-tab">

			<h3 class="control-sidebar-heading">Ajuda</h3>
			<ul class='control-sidebar-menu'>
				<li>
					<a href="javascript::;">
						<i class="menu-icon fa fa-book bg-blue"></i>
						<div class="menu-info">
							<h4 class="control-sidebar-subheading">Manual de Preenchimento</h4>
							<p>Manual de Preenchimento</p>
						</div>
					</a>
				</li>
				<li>
					<a href="javascript::;">
						<i class="menu-icon fa fa-info bg-green"></i>
						<div class="menu-info">
							<h4 class="control-sidebar-subheading">Sobre o SNIS</h4>
							<p>Sobre o SNIS</p>
						</div>
					</a>
				</li>
			</ul>
		</div>
		<!-- /.tab-pane -->
	</div>
</aside><!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
	 immediately after the control sidebar -->
<div class='control-sidebar-bg'></div>