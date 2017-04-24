<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap\ActiveForm;
use projeto\helpers\Html;
use projeto\web\View;
?>
<br />
<div class="login-box">
	<div class="login-box-body">
		<div class="row">
			<div class="col-md-12" style="color: red; font-weight: 600; font-size: 26px; text-align: center;">
				Águas Pluviais - Coleta de dados 2015 encerrada
				<br>
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6" style="border-right: 1px solid #CCC;">	
				<p><b><?= Html::ficon('key', 'Informe os dados abaixo para acessar o sistema') ?></b></p>
				<?php
				$form = ActiveForm::begin([
						'id' => 'login-form',
						'fieldConfig' => [
							'template' => "<div>{input}</div>\n<div>{error}</div>",
							'labelOptions' => ['class' => 'col-lg-1 control-label'],
						],
				]);
				?>
				<div class="form-group has-feedback">
					<?= $form->field($model, 'txt_login')->textInput(['placeholder' => "CPF/Login"]); ?>
					<span class="glyphicon glyphicon-user form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<?= $form->field($model, 'txt_senha')->passwordInput(['placeholder' => "Senha"]) ?>
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>

				<div class="form-group">
					<?php /* if (false): ?>
					  <div class="row">
					  <div class="col-md-12">
					  <?= \himiklab\yii2\recaptcha\ReCaptcha::widget([
					  'name' => 'reCaptcha',
					  'widgetOptions' => ['class' => 'col-md-2']
					  ]) ?>
					  </div>
					  </div>
					  <p>&nbsp;</p>
					  <?php endif */ ?>

					<div class="row">
						<div class="col-md-6">
							<?=
							Html::a(Html::icon('refresh', 'Esqueceu a senha?'), 'recuperar-senha', [
								'class' => 'btn btn-default btn-block btn-flat'
							])
							?>
						</div>
						<div class="col-md-6">
							<?=
							Html::submitButton(Html::icon('log-in', '&nbsp;Entrar'), [
								'class' => ' btn btn-success btn-block btn-flat',
								'name' => 'login-button'
							])
							?>
						</div>
					</div>

					<div class="row">
                        <div class="col-md-12">
                            <?php
                            $m = app\modules\admin\models\TabModulosSearch::getEquipesModulosInicio();
                            if ($m) {
                                if (count($m) > 1) {
                                    echo "<div class='pre-scrollable' style='margin-top: 10px; height: 130px;'>";
                                }
                                foreach ($m as $value) {
                                    echo "<hr /><p><b>{$value['txt_equipe']}</b></p>";
                                    echo "<p><i class='fa fa-envelope'></i> " . Html::a($value['txt_email_equipe'], 'mailto:' . $value['txt_email_equipe'] . '') . "</p>";
                                    echo "<p>" . Html::icon('phone-alt', $value['num_fones_equipe']) . "</p>";
                                }
                                if (count($m) > 1) {
                                    echo "</div>";
                                }
                            }
                            ?>
						</div>
					</div>
				</div>

<?php ActiveForm::end(); ?>

			</div>

			<div class="col-md-6">
				<p><b><?= Html::ficon('info-circle', 'Nos ajude a tornar o SNIS ainda melhor!') ?> </b></p>
				<br>
				<p>Mantenha seu navegador atualizado, proteja-se e amplie sua experiência na web.</p>
				<p class="text-justify">Para uma melhor experiência com o SNIS utilize um navegador atualizado. 
					Sugerimos que você atualize a versão do navegador ou instale a última versão de algum dos abaixo sugeridos:</p>
				<br>
				<div style="text-align:center">
					<table align="center">
						<tr>
							<td style="width:70px; text-align:center;">
								<a href="https://www.google.com/chrome" target="_blank">
<?= Html::img('@web/img/navegadores/chrome/chrome_32x32.png') ?>
								</a>
								<a href="https://www.google.com/chrome" target="_blank" style="color:blue">
									Google Chrome
								</a>
							</td>
							<td style="width:70px; text-align:center;">
								<a href="http://br.mozdev.org/firefox/download/" target="_blank">
<?= Html::img('@web/img/navegadores/firefox/firefox_32x32.png') ?>
								</a>
								<a href="http://br.mozdev.org/firefox/download/" target="_blank" style="color:blue">
									Mozilla Firefox
								</a>
							</td>
							<td style="width:70px; text-align:center;">
								<a href="http://www.microsoft.com/windows/Internet-explorer/default.aspx" target="_blank">
<?= Html::img('@web/img/navegadores/internet-explorer/internet-explorer_32x32.png') ?>
								</a>
								<a href="http://www.microsoft.com/windows/Internet-explorer/default.aspx" target="_blank" style="color:blue">
									Internet Explorer
								</a>
							</td>
							<?php /*
							  <td style="width:70px; text-align:center;">
							  <a href="" target="_blank">
							  <?= Html::img('@web/img/navegadores/safari/safari_32x32.png') ?>
							  </a>
							  <a href="" target="_blank" style="color:blue">
							  Apple Safari
							  </a>
							  </td>
							 */ ?>
							<td style="width:70px; text-align:center;">
								<a href="http://www.opera.com/pt-br/download" target="_blank">
<?= Html::img('@web/img/navegadores/opera/opera_32x32.png') ?>
								</a>
								<a href="http://www.opera.com/pt-br/download" target="_blank" style="color:blue">
									Opera<br>&nbsp;
								</a>
							</td>
						</tr>
					</table>
				</div>
			</div>

		</div>
	</div>
</div>

<?php $this->registerJs("$('#mdlusuarios-txt_login').focus();", View::POS_READY) ?>

