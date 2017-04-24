<?php

use yii\helpers\Url;
use \projeto\helpers\Html;
use yii\bootstrap\Modal;
use app\models\TabParametrosSearch;
use \app\modules\admin\models\TabModulos;

$modulosPorlinha = 3;
$contadorModulos = 0;

$bloqueadoInterno=[];
$bloqueadoExterno=[];
$totBloqueados=0;
foreach ($modulos as $modulo) {
	$bloqueadoInterno[$modulo['modulo_id']] = \projeto\base\Module::isModuloBloqueadoAcessoInterno($modulo['modulo_id'], $modulo['cod_usuario_fk']);
	$bloqueadoExterno[$modulo['modulo_id']] = \projeto\base\Module::isModuloBloqueadoAcessoExterno($modulo['modulo_id'], $modulo['cod_usuario_fk']);
	
	if ($bloqueadoInterno[$modulo['modulo_id']]) {
		$totBloqueados++;
	}
	if ($bloqueadoExterno[$modulo['modulo_id']]) {
		$totBloqueados++;
	}
}

?>


<?php ## se primeiro acesso do usuario com perfil administrador prestador mostra modal para cadastro de usuario ## ?>
<?php if (Yii::$app->user->identity->qtd_acesso <= 1 && $txt_perfil_prestador == 1): ?>
	<?= $this->render('_pergunta_novo_usuario') ?>
<?php endif; ?>

<div class="site-index">

	<?php if ($totBloqueados > 0): ?>
		<div class="alert alert-warning">
			ATENÇÃO: os módulos abaixo destacados em vermelho se encontram bloqueados para acesso
		</div>
	<?php endif ?>
	
    <div class="box box-default">
		<div class="box-header with-border">
			<?= Html::icon('info-sign') ?>
            Veja abaixo os módulos que você poderá acessar. 
            Clique sobre o módulo para obter maiores informações 
            e também acessar os seus formulários e funcionalidades.
		</div>
		<div class="box-body">
			<div>
				<div class="row">
					<?php if (!$modulos || !$modulos[0]['cod_perfil_fk']): ?>
						<div class="alert alert-info" role="alert">Nenhum módulo disponível para o seu usuário.</div>
					<?php else: ?>
						<?php foreach ($modulos as $modulo): ?>
							<div class="col-md-4 ">
								<div class="caixa-modulo caixa-modulo-<?= $modulo['modulo_id'] ?>">
									<div class="box box-danger">
										<div class="box-header with-border text-center">
											<?php if ($bloqueadoInterno[$modulo['modulo_id']] || $bloqueadoExterno[$modulo['modulo_id']]): ?>
												<?= $modulo['nome_modulo'] ?>
											<?php else: ?>
												<?= Html::a($modulo['nome_modulo'], Url::toRoute($modulo['modulo_url']), ['style' => ['display' => 'block']]) ?>
											<?php endif ?>
										</div>
										<div class="box-body text-center">
											<?php
											$img = Html::img($modulo['modulo_icone'], [
													'width'	 => '80px',
													'class'	 => 'img-circle',
													'height' => '80px',
												])
											?>
											<?php if ($bloqueadoInterno[$modulo['modulo_id']] || $bloqueadoExterno[$modulo['modulo_id']]): ?>
												<?= Html::a($img, 'javascript://;', ['style' => ['display' => 'block', 'cursor' => 'default']]) ?>
												<span style="color:red">
													Módulo bloqueado para acesso 
													<?php if ($bloqueadoInterno[$modulo['modulo_id']]): ?>interno<?php endif ?>
													<?php if ($bloqueadoExterno[$modulo['modulo_id']]): ?>externo<?php endif ?>
												</span>
											<?php else: ?>
												<?= Html::a($img, Url::toRoute($modulo['modulo_url']), ['style' => ['display' => 'block']]) ?>
											<?php endif ?>
										</div>
										<div class="box-footer text-center">
											<?= $modulo['dsc_modulo'] ?>
										</div>
										<div class="box-footer text-center">
                                            <?php
                                            $m = app\modules\admin\models\TabModulosSearch::getEquipeModuloId($modulo['modulo_id']);
                                            if ($m) {
                                                echo "<p><b>{$m['txt_equipe']}</b></p>";
                                                echo "<p><i class='fa fa-envelope'></i> " . Html::a($m['txt_email_equipe'], 'mailto:' . $m['txt_email_equipe'] . '') . "</p>";
                                                echo "<p>" . Html::icon('phone-alt', $m['num_fones_equipe']) . "</p>";
                                            }
                                            ?>
										</div>
									</div>
								</div>

							</div>
							<?php $contadorModulos++; ?>
							<?php if ($contadorModulos == $modulosPorlinha): ?>
								<?php $contadorModulos = 0; ?>
								</div>
								<div class="row">
							<?php endif; ?>

						<?php endforeach; ?>
					<?php endif; ?>
				</div> <!-- /div.row -->
			</div>
		</div>
	</div>
</div>

