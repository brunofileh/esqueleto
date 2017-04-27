<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\MenuBarraTopoModuloWidget;

use yii\web\JsExpression;
use kartik\widgets\Typeahead;

?>

<header class="main-header">
    <?php
    $modulos = \app\modules\admin\models\VisUsuariosPerfisSearch::getModulosPerfisUsuario($this->user->identity->getId());
    $opt = [];
    $opt['class'] = 'logo';
    if (count($modulos) <= 1) {
        $opt['style'] = 'pointer-events: none; cursor: default;';
    }
    echo Html::a(
        '<span class="logo-mini">' 
            . (YII_ENV_PROD ? $this->app->params['nome-sistema'] : "{$this->app->params['nome-sistema']}")
            . '</span><span class="logo-lg">'
            . ($this->app->params['nome-sistema'])
            . '<span style="color: #FF0000;">' . (YII_ENV_PROD ? null : "-" . YII_ENV) . '</span>'
        .'</span>', 
        $this->app->homeUrl, 
        $opt
    );
    ?>
    
	<nav class="navbar navbar-static-top" role="navigation">

		<a href="javascript://;" class="sidebar-toggle" data-toggle="offcanvas" role="button">
			<span class="sr-only">Toggle navigation</span>
		</a>
		<?php if (isset($this->context->module->info)): ?>
			<div class="navbar-custom-menu pull-left">
				<?= MenuBarraTopoModuloWidget::widget(['modulo_id' => $this->app->controller->module->info['cod_modulo']]); ?>
			</div>
		<?php endif; ?>
		
		<?php if ($this->context->module->id == 'drenagem'): ?>
			<form class="navbar-form navbar-left" role="search">
				<div class="form-group">
					<?php 
						$templateResultados = 
							'<div>'
								. '<p class="tt-grupo">{{grupo}}</p>'
								. '<p class="tt-id">{{valor}}</p>'
								. '<p class="tt-descr">{{descricao}}</p>'
							.'</div>'
						;
					?>
					<?php 
						$placeholder = 'Ir para: Aviso/Erro - Indicador - Informação';
						if (null == $this->app->user->identity->cod_prestador_fk) {
							$placeholder .= ' - Município';
						}
					?>
					<?= Typeahead::widget([
						'name' => 'busca_global', 
						'options' => [
							'placeholder' => $placeholder,
							'style' => ['width' => '400px'],
						],
						'pluginOptions' => ['highlight'=>true],
						'pluginEvents' => [
							'typeahead:select' => 'function(evt, obj){

								/* caso jah esteja na pagina só faz o scroll 
								if (obj.grupo_id == "informacao" && location.pathname.indexOf("/glossario/") > 0) {
									projeto.util.scrollTo(obj.id);
									return;
								}
								else if (obj.grupo_id == "aviso_erro" && location.pathname.indexOf("/avisos-erros/") > 0) {
									projeto.util.scrollTo(obj.id);
									return;
								}
								else if (obj.grupo_id == "indicador" && location.pathname.indexOf("/glossarios-indicadores/") > 0) {
									projeto.util.scrollTo(obj.id);
									return;
								}
								else {
									var base = $("base").prop("href");
									switch (obj.grupo_id) {
										case "informacao" :
											projeto.ajax.defaultBlockUI()
											window.location = base + "drenagem/glossario/print?r=" + obj.id;
											break;
										case "aviso_erro" :
											projeto.ajax.defaultBlockUI()
											window.location = base + "drenagem/avisos-erros/print?r=" + obj.id;
											break;
										case "indicador" :
											projeto.ajax.defaultBlockUI()
											window.location = base + "drenagem/glossarios-indicadores/print?r=" + obj.id;
											break;
										case "municipio" :
											projeto.ajax.defaultBlockUI()
											var c = obj.id.split("|");
											window.location = base + "drenagem/consulta-prestadores?cod_prestador={psv}&cod_participacao={part}"
												.replace("{psv}", c[0])
												.replace("{part}", c[1])
											;
											break;
									}
								}*/
							}'
						],
						'dataset' => [[
							'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
							'display' => 'value',
							'limit' => 10,
							'remote' => [
								'url' => yii\helpers\Url::to(['/drenagem/consulta-prestadores/busca-global']) . '?q=%QUERY',
								'wildcard' => '%QUERY',
							],
							'templates' => [
								'notFound' => '<div class="text-danger" style="padding:0 8px">Nenhuma correspondência encontrada</div>',
								'suggestion' => new JsExpression("Handlebars.compile('{$templateResultados}')")
							],
						]],
					]) ?>
				</div>
			</form>
		<?php endif ?>
		
		<div class="navbar-custom-menu pull-right">
			<ul class="nav navbar-nav">
				<?php if (!Yii::$app->user->isGuest): ?>
					<li class="dropdown user user-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<?= Html::img(Yii::$app->user->identity->txt_imagem, [
									'class' => 'img-circle user-image',
									'alt' => Yii::$app->user->identity->txt_email,
								]) ?>

							<span class="hidden-xs"> <?= Yii::$app->user->identity->txt_nome ?></span>
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<!-- User image -->
							<li class="user-header">
								<?= Html::img(Yii::$app->user->identity->txt_imagem, [
									'class' => 'img-circle',
									'alt' => Yii::$app->user->identity->txt_email,
								]) ?>
								<p>
									<?= Yii::$app->user->identity->txt_email ?>
									<?php if (isset( $this->context->module->info )) : ?>
										<small><?= $this->context->module->info['usuario-perfil']['nome_perfil']; ?><br /> </small> 
									<?php endif; ?>
								</p>
							</li>
							<li class="user-footer">
								<div class="row">
									<div class="col-xs-6 text-center">
										<?= Html::a('Minha conta', Url::toRoute('/usuarios/view'), ['class' => 'btn btn-flat']) ?>
									</div>
									<div class="col-xs-6 text-center">
										<?= Html::a('Sair', Url::toRoute('/sair'), [
											'class' => 'btn btn-flat', 
											'data-method' => 'post'
										]) ?>
									</div>
								</div>
							</li>
						</ul>
					</li>

					<!-- User Account: style can be found in dropdown.less -->
					<li class="dropdown">
						<a href="javascript://;" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Ajuda <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li>
								<a href="//www.snis.gov.br/downloads/manuais-atualizados/drenagem/Manual_Preenchimento_DG2015.pdf" target="_blank"><i class="menu-icon fa fa-book"></i> Manual de Preenchimento</a>
							</li>
						</ul>
					</li>
				
				<?php else: ?>
					<li><?= Html::a('Entrar', Url::toRoute('/entrar')) ?></li>
				<?php endif; ?>
			</ul>
		</div>
	</nav>
</header>
