<?php

namespace projeto\web;

use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;
use yii\bootstrap\Modal;
use kartik\daterange\DateRangePicker;
use kartik\money\MaskMoney;
use projeto\Util;
use projeto\helpers\Html;
use projeto\db\ActiveRecord;
use app\models\VisAtributosValores;

class ActiveForm extends \kartik\form\ActiveForm {

	use \projeto\Atalhos;

	public $fieldClass = 'projeto\web\ActiveField';
	public $numLinhasTextareaPequeno = 5;
	public $numLinhasTextareaGrande = 8;
	public $labelSpan = 6;
	public $templateDefault = true;
	public $showHints = true;

	public function init() {
		parent::init();
		$this->configAtalhos();
	}

	/**
	 * Runs the widget.
	 * This registers the necessary javascript code and renders the form close tag.
	 * @throws InvalidCallException if `beginField()` and `endField()` calls are not matching
	 */
	public function run() {

		if (!empty($this->_fields)) {
			throw new InvalidCallException('Each beginField() should have a matching endField() call.');
		}

		$content = ob_get_clean();
		echo Html::beginForm($this->action, $this->method, $this->options);
		echo $content;

		if ($this->enableClientScript) {
			$id = $this->options['id'];

			$options = \yii\helpers\Json::htmlEncode($this->getClientOptions());

			$attributes = \yii\helpers\Json::htmlEncode($this->attributes);

			$view = $this->getView();

			\projeto\widgets\ActiveFormAsset::register($view);

			$view->registerJs("jQuery('#$id').yiiActiveForm($attributes, $options);");
		}

		echo Html::endForm();
	}

	public function initForm() {
		$this->type = ActiveForm::TYPE_HORIZONTAL;
		$this->fieldConfig['class'] = $this->fieldClass;
		$this->formConfig['labelSpan'] = 8; //$this->labelSpan;

		$this->formConfig['showHints'] = $this->showHints;
		$this->formConfig['labelSpan'] = $this->labelSpan;

		if (!$this->templateDefault) {
			$this->fieldConfig['template'] = '{label}{input}{error}{hint}';
			$this->fieldConfig['marginRightError'] = '0';
		}

		parent::initForm();
		Html::addCssClass($this->options, 'form-coleta');
	}

	protected function getLabelColeta(array $glossario) {
		$codInfo = strtolower($glossario['sgl_cod_info']);
		$icone = Html::ficon('plus');
		$script = "javascript:projeto.toggleInfo('$codInfo');";
		$opt = [
			'class' => "btn-info-{$codInfo}",
			'title' => 'Mais informações',
			'onclick' => $script
		];
		$link = ($this->templateDefault) ? Html::a($icone, 'javascript://;', $opt) : '';

		return $link
			. " {$glossario['sgl_cod_info']}"
			. Html::tag('span', ' ' . $glossario['dsc_nom_info'])
			. ($glossario['bln_obrigatorio'] ? '&nbsp;' . Html::tag('span', '*', [
					'data-toggle' => 'tooltip',
					'data-placement' => 'right',
					'title' => 'Campo de preenchimento obrigatório',
					'style' => [
						'color' => 'red',
						'font-weight' => 'bold',
						'font-size' => '14px'
					]
				]) : '')
		;
	}

	public function getApresentacaoCampo($model, $codInfo) {
		$glossario = $model->glossario($codInfo);
		$tipoCampo = null;
		$optCampo = null;

		// ex: input:email
		if (strpos($glossario['sgl_tipo_apresentacao'], ':') !== false) {
			$tipoCampo = explode(':', $glossario['sgl_tipo_apresentacao'])[0];
			$optCampo = explode(':', $glossario['sgl_tipo_apresentacao'])[1];
		} else {
			// ex: select
			$tipoCampo = $glossario['sgl_tipo_apresentacao'];
			$optCampo = $glossario['sgl_opt_apresentacao'];
		}

		return [
			$tipoCampo,
			$optCampo
		];
	}

	public function campoColeta(ActiveRecord $model, $codInfo, array $options = [], $alertas = []) {
		$objRetorno = null;
		$glossario = $model->glossario($codInfo);

		if ($glossario['bln_info_ativa'] == true) {
			if (is_string($model->$codInfo)) {
				$model->$codInfo = trim($model->$codInfo);
			}

			list($tipoCampo, $optCampo) = $this->getApresentacaoCampo($model, $codInfo);

			$hint = $glossario['dsc_det_info'];
			$label = $this->getLabelColeta($glossario);

			$dadosPadrao = [
				'model' => $model,
				'codInfo' => $codInfo,
				'glossario' => $glossario,
				'tipoCampo' => $tipoCampo,
				'optCampo' => $optCampo,
				'options' => $options,
				'hint' => $hint,
				'label' => $label,
				'alertas' => $model->alertas,
				'classAE' => (($model->$codInfo) ? 'has-success-2' : 'has-default-2')
			];

			switch ($tipoCampo) {
				case 'input' :
					$objRetorno = $this->campoColetaInput($dadosPadrao);
					break;

				case 'radiolist' :
					$objRetorno = $this->campoColetaRadioList($dadosPadrao);
					break;

				case 'dropdownlist' :
					$objRetorno = $this->campoColetaDropDownList($dadosPadrao);
					break;

				case 'checkboxlist' :
					$objRetorno = $this->campoColetaCheckboxList($dadosPadrao);
					break;

				case 'multiselect' :
					$objRetorno = $this->campoColetaMultiSelect($dadosPadrao);
					break;

				case 'textarea' :
					$objRetorno = $this->campoColetaTextarea($dadosPadrao);
					break;

				case 'hidden' :
					$objRetorno = $this->campoColetaHiddenInput($dadosPadrao);
					break;

				default :
					$msg = "Tipo de campo (codInfo) não reconhecido no glossário: {$glossario['dsc_apresentacao']}";
					throw new NotSupportedException($msg);
			}
		}

		return $objRetorno;
	}

	protected function getTotalAvisosPorCampo(array $config) {
		$avisosCount = 0;

		foreach ($config['model']['alertas']['avisos'] as $codAviso => $params) {
			foreach ($params['sgl_cod_info'] as $info) {
				if ($info == $config['glossario']['sgl_cod_info']) {
					$avisosCount++;
				}
			}
		}

		return $avisosCount;
	}

	protected function getTotalErrosPorCampo(array $config) {
		$errosCount = 0;
		foreach ($config['model']['alertas']['erros'] as $codErro => $params) {
			if ($codErro == 'E000') {
				foreach ($params as $item) {
					if ($item['sgl_cod_info'][0] == $config['glossario']['sgl_cod_info']) {
						foreach ($item['sgl_cod_info'] as $info) {
							if ($info == $config['glossario']['sgl_cod_info']) {
								$errosCount++;
							}
						}
					}
				}
			} else {
				foreach ($params['sgl_cod_info'] as $info) {
					if ($info == $config['glossario']['sgl_cod_info']) {
						$errosCount++;
					}
				}
			}
		}

		return $errosCount;
	}

	public static function getTabelaAvisosErros($item, $dscTipo) {

		if (!isset($item['sgl_cod_info'])) {
			$item = array_shift($item);
		}

		//	


		$html = null;
		if (isset($item['linha'])) {
			$html = "
			<tr>
                <td><b>Tabela</b></td>
                <td>{$item['tabela']}</td>
            </tr>
			<tr>
                <td><b>Linha(s)</b></td>
                <td>" . implode(', ', $item['linha']) . "</td>
            </tr>";
		}

		foreach ($item['sgl_cod_info'] as $value) {

			//$tootip = \app\models\TabGlossariosSearch::findOneAsArray(['sgl_cod_info'=>$value, 'bln_info_ativa' => true, 'num_ano_ref'=>  \Yii::$app->params['ano-ref']]);
			$campos[] = "<a data-toggle='tooltip' data-placement='center' data-original-title='{$item['tooltip'][$value]}'>{$value}</a>";
		}

		$campos = implode(', ', $campos);

		return "
            <tr>
                <td width=\"5%\">{$dscTipo}</td>
                <td width=\"5%\">{$item['txt_codigo']}</td>
                <td width=\"18%\">{$campos}</td>
                <td width=\"48%\">{$item['txt_descricao']}</td>
                <td width=\"24%\">{$item['txt_formula']}</td>
            </tr>
            {$html}
        ";
	}

	public static function getModalAvisosErros($codigo, $model) {
		$tabela = "<table class=\"table table-striped\">
            <tr>
                <td><b>Tipo</b></td>
                <td><b>Código</b></td>
                <td><b>Campos vinculados</b></td>
                <td><b>Descrição</b></td>
                <td><b>Fórmula</b></td>
            </tr>";

		// Para avisos
		if ($model->alertas['avisos']) {

			foreach ($model->alertas['avisos'] as $codAviso => $params) {
				if (in_array($codigo, $params['sgl_cod_info']) || in_array($codigo, ['A', 'E'])) {
					$tabela .= ActiveForm::getTabelaAvisosErros($params, 'Aviso');
					$r = true;
				}
			}
		}

		// erros E000
		if (isset($model->alertas['erros']['E000'])) {
			foreach ($model->alertas['erros']['E000'] as $codErro => $params) {
				if ($codErro == $codigo || in_array($codigo, ['A', 'E'])) {
					$tabela .= ActiveForm::getTabelaAvisosErros($params, 'Erro');
					$r = true;
				}
			}
		}
		// demais erros
		if ($model->alertas['erros']) {
			foreach ($model->alertas['erros'] as $codErro => $params) {
				if ($codErro == 'E000') {
					continue;
				}

				if (in_array($codigo, $params['sgl_cod_info']) || in_array($codigo, ['A', 'E'])) {
					$tabela .= ActiveForm::getTabelaAvisosErros($params, 'Erro');
					$r = true;
				}
			}
		}

		$tabela .= "</table>";

		return $r ? $tabela : '';
	}

	public static function getModalAvisosGerais($codigo, $model) {
		$r = false;
		$tabela = "<table class=\"table table-striped\">
            <tr>
                <td><b>Tipo</b></td>
                <td><b>Código</b></td>
                <td><b>Campos vinculados</b></td>
                <td><b>Descrição</b></td>
                <td><b>Fórmula</b></td>
            </tr>";

		// Para avisos
		if ($codigo == 'A') {
			if (isset($model->alertas['avisos'])) {

				foreach ($model->alertas['avisos'] as $codAviso => $params) {
					if (in_array($codigo, $params['sgl_cod_info']) || in_array($codigo, ['A', 'E'])) {
						$tabela .= ActiveForm::getTabelaAvisosErros($params, 'Aviso');
						$r = true;
					}
				}
			}
		}

		// erros E000
		if ($codigo == 'E') {
			if (isset($model->alertas['erros']['E000'])) {
				foreach ($model->alertas['erros']['E000'] as $codErro => $params) {
					if ($codErro == $codigo || in_array($codigo, ['A', 'E'])) {
						$tabela .= ActiveForm::getTabelaAvisosErros($params, 'Erro');
						$r = true;
					}
				}
			}
			// demais erros

			if (isset($model->alertas['erros'])) {
				foreach ($model->alertas['erros'] as $codErro => $params) {
					if ($codErro == 'E000') {
						continue;
					}

					if (in_array($codigo, $params['sgl_cod_info']) || in_array($codigo, ['A', 'E'])) {
						$tabela .= ActiveForm::getTabelaAvisosErros($params, 'Erro');
						$r = true;
					}
				}
			}
		}

		$tabela .= "</table>";

		return $r ? $tabela : '';
	}

	public static function getModalsAvisosErros($codigo, $model) {
		$tabela = null;

		// Para gerar modal do formulário
		if ($codigo == 'A') {

			$tabela .= self::getModalAvisosGerais($codigo, $model);

			if (!empty($model->associativas)) {
				foreach ($model->associativas as $assoc) {
					foreach ($model->$assoc as $key => $assocClass) {
						$tabela .= self::getModalAvisosGerais('A', $assocClass);
					}
				}
			}
		}
		if ($codigo == 'E') {

			$tabela .= self::getModalAvisosGerais($codigo, $model);

			if ($model->associativas) {

				foreach ($model->associativas as $assoc) {
					foreach ($model->$assoc as $key => $assocClass) {
						$tabela .= self::getModalAvisosGerais('E', $assocClass);
					}
				}
			}
		}

		return $tabela;
	}

	protected function addonPadrao(array $config, &$classAE) {
		// Variáveis iniciais
		$icon = 'check';
		$color = 'lightgray';
		$abre_link = null;
		$fecha_link = null;
		$sgl_cod_info = strtolower($config['glossario']['sgl_cod_info']);
		$flag = 0;
		$model = $config['model'];

		// Verifica avisos
		$avisosCount = 0;
		if (isset($config['alertas']['avisos'])) {
			$avisosCount = $this->getTotalAvisosPorCampo($config);
		}

		// Verifica erros
		$errosCount = 0;
		if (isset($config['alertas']['erros'])) {
			$errosCount = $this->getTotalErrosPorCampo($config);
		}

		if ($avisosCount > 0 && $errosCount == 0) {
			$icon = 'exclamation';
			$color = '#9C6500';
			$flag = 1;
			$titulo = 'Avisos';
			$classAE = 'has-aviso-2';
		}

		if ($errosCount > 0) {
			$icon = 'remove';
			$color = 'red';
			$flag = 1;
			$titulo = ($avisosCount > 0) ? 'Avisos e Erros' : 'Erros';
			$classAE = 'has-error-2';
		}

		// Modal para o campo
		if ($flag == 1) {
			$modalId = 'modal-campo-' . $sgl_cod_info;
			$tituloModal = "{$titulo} do campo {$config['glossario']['sgl_cod_info']}";

			$html = $this->getModalAvisosErros($config['glossario']['sgl_cod_info'], $model);

			$this->gerarModal($modalId, $tituloModal, $html);

			$abre_link = '<a href="javascript://;" data-toggle="modal" data-target="#' . $modalId . '">';
			$fecha_link = '</a>';
		}

		// Verifica se o campo foi preenchido e não existem avisos e erros
		if ($config['model']->$sgl_cod_info != null && $config['model']->$sgl_cod_info != [] && $errosCount == 0 && $avisosCount == 0
		) {
			$color = 'green';
		}
		$calculavel = null;
		if ($config['glossario']['bln_calculavel'] || $config['glossario']['bln_obrigatorio']) {
			$calculavel = ' addonCalculavel';
		}

		$addon['prepend'] = [
			'content' => $abre_link . '<div class="addon-valida"><i class="fa fa-' . $icon . '"></i></div>' . $fecha_link,
			'options' => [
				'style' => 'border-color:#fff',
				'class' => 'addonAE' . $calculavel,
			]
		];

		// Formulários que não devem aparecer o append
		$formularios_sem_append = [
			'public.tab_prestadores',
			'public.rlc_modulos_prestadores'
		];

		// Habilita o append somente para os formulários que devem aparecer
		if (!in_array($config['glossario']['sgl_tabela_db'], $formularios_sem_append)) {
			$addon['append'] = [
				'content' => '<div class="addon-unidade" data-toggle="tooltip" data-placement="bottom" title="' . $config['glossario']['dsc_und_info'] . '">' . ($config['glossario']['sgl_und_info'] ? '<b>' . $config['glossario']['sgl_und_info'] . '</b>' : '&nbsp;') . '</div>',
				'options' => [
					'style' => 'border-color:#fff'
				]
			];
		}

		return $addon;
	}

	protected function getCorCampo(array $config) {
		// Variáveis iniciais
		$cor_campo['border_color'] = '#ddd';
		$cor_campo['background'] = 'white'; //''

		$avisosCount = 0;
		if (isset($config['alertas']['avisos'])) {
			$avisosCount = $this->getTotalAvisosPorCampo($config);
		}

		$errosCount = 0;
		if (isset($config['alertas']['erros'])) {
			$errosCount = $this->getTotalErrosPorCampo($config);
		}

		if ($avisosCount > 0 && $errosCount == 0) {
			$cor_campo['border_color'] = '#9C6500';
			$cor_campo['background'] = '#FFEB9C';
		}

		if ($errosCount > 0) {
			$cor_campo['border_color'] = 'red';
			$cor_campo['background'] = '#ffc7ce';
		}

		return $cor_campo;
	}

	protected function campoColetaTextarea(array $config) {

		$classAE = $config['classAE'];
		$options = ArrayHelper::merge($config['options'], [
				'addon' => $this->addonPadrao($config, $classAE)
		]);

		$cor_campo = $this->getCorCampo($config);

		list($tipoCampo, $optCampo) = $this->getApresentacaoCampo($config['model'], $config['codInfo']);
		$rows = null;

		if ($optCampo == 'p') {
			$rows = $this->numLinhasTextareaPequeno;
		} elseif ($optCampo == 'g') {
			$rows = $this->numLinhasTextareaGrande;
		}

		return $this->field($config['model'], $config['codInfo'], $options)
				->label($config['label'])
				->hint($config['hint'])
				->options($classAE)
				->textarea([
					'rows' => $rows,
					'class' => 'avisoErro'
					] /* [
					  'style' => 'border-color:' . $cor_campo['border_color'] . '; background:' . $cor_campo['background'] . ''
					  ] */
				)
		;
	}

	protected function campoColetaMultiSelect(array $config) {
		$classAE = $config['classAE'];
		$options = ArrayHelper::merge($config['options'], [
				'addon' => $this->addonPadrao($config, $classAE)
		]);

		$cor_campo = $this->getCorCampo($config);

		return $this->field($config['model'], $config['codInfo'], $options)
				->label($config['label'])
				->hint($config['hint'])
				->options($classAE)
				->multiselect(VisAtributosValores::getOpcoes($config['optCampo']), [
					'style' => 'border-color:' . $cor_campo['border_color'] . '; background:' . $cor_campo['background'] . ''
				])
		;
	}

	protected function campoColetaCheckboxList(array $config) {
		$classAE = $config['classAE'];
		$options = ArrayHelper::merge($config['options'], [
				'addon' => $this->addonPadrao($config, $classAE)
		]);

		$cor_campo = $this->getCorCampo($config);

		if ($config['glossario']['bln_calculavel'] || $config['glossario']['bln_obrigatorio']) {
			$opcoesField = [
				'class' => 'avisoErro checkboxAE',
				'campo' => strtolower($config['glossario']['sgl_cod_info']),
			];
		} else {
			$opcoesField = [
				'class' => 'checkboxAE',
			];
		}

		return $this->field($config['model'], $config['codInfo'], $options)
				->label($config['label'])
				->hint($config['hint'])
				->options($classAE)
				->checkboxList(VisAtributosValores::getOpcoes($config['optCampo'], null, '"sgl_valor"::integer'), $opcoesField);
	}

	protected function campoColetaDropDownList(array $config) {

		$classAE = $config['classAE'];
		$options = ArrayHelper::merge($config['options'], [
				'addon' => $this->addonPadrao($config, $classAE)
		]);

		$cor_campo = $this->getCorCampo($config);

		if ($config['glossario']['bln_calculavel'] || $config['glossario']['bln_obrigatorio']) {
			$opcoesField = [
				'class' => 'avisoErro',
				'campo' => strtolower($config['glossario']['sgl_cod_info']),
				//	 'style' => 'border-color:' . $cor_campo['border_color'] . '; background:' . $cor_campo['background'] . '',
			];
		} else {
			$opcoesField = [
				//'style' => 'border-color:' . $cor_campo['border_color'] . '; background:' . $cor_campo['background'] . '',
			];
		}

		return $this->field($config['model'], $config['codInfo'], $options)
				->label($config['label'])
				->hint($config['hint'])
				->options($classAE)
				->dropDownList(ArrayHelper::merge([
						'' => $this->app->params['txt-prompt-select']
						], (($config['optCampo']) ? VisAtributosValores::getOpcoes($config['optCampo']) : [])
					), $opcoesField
				)
		;
	}

	protected function campoColetaHiddenInput(array $config) {
		return $this->field($config['model'], $config['codInfo'], $config['options'])
				->label($config['label'])
				->hint($config['hint'])
				->hiddenInput(ArrayHelper::merge([
						'' => $this->app->params['txt-prompt-select']
						], (($config['optCampo']) ? VisAtributosValores::getOpcoes($config['optCampo']) : [])
					)
				)
		;
	}

	protected function campoColetaRadioList(array $config) {
		$classAE = $config['classAE'];
		$options = ArrayHelper::merge($config['options'], [
				'addon' => $this->addonPadrao($config, $classAE)
		]);

		$cor_campo = $this->getCorCampo($config);
		if ($config['glossario']['bln_calculavel'] || $config['glossario']['bln_obrigatorio']) {
			$opcoesField = [
				'class' => 'avisoErro checkboxAE',
				'campo' => strtolower($config['glossario']['sgl_cod_info']),
			];
		} else {
			$opcoesField = [
				'class' => 'checkboxAE',
			];
		}

		return $this->field($config['model'], $config['codInfo'], $options)
				->label($config['label'])
				->hint($config['hint'])
				->options($classAE)
				->radioList(VisAtributosValores::getOpcoes($config['optCampo']), $opcoesField)
		;
	}

	protected function campoColetaInput(array $config) {
		if (is_null($config['optCampo'])) {
			throw new NotSupportedException("Opção inválida: {$config['glossario']['dsc_apresentacao']}");
		}

		$classAE = $config['classAE'];

		$options = ArrayHelper::merge($config['options'], [
				'addon' => $this->addonPadrao($config, $classAE),
		]);

		$cor_campo = $this->getCorCampo($config);
		$estilo_padrao = 'border-color:' . $cor_campo['border_color'] . '; background:' . $cor_campo['background'] . '';

		switch ($config['optCampo']) {
			case 'text':

				$inputOptions = [];
				$class = 'text-left';
				$disabled = false;

				if ($config['glossario']['bln_calculavel'] || $config['glossario']['bln_obrigatorio']) {
					$class .= ' avisoErro';
				}

				// Para campos totalizadores
				if ($config['glossario']['sgl_tipo_origem'] == 'T') {
					$class = 'text-right';
					$disabled = true;
					$formula = $config['glossario']['txt_formula_tot'];

					$r = preg_replace_callback('/([a-zA-Z]{2})([0-9]{3})/', function ($matches) use ($config) {
						return $config['model']->{$matches[0]} ? : 0;
					}, $formula);
					$v = eval('return ' . $r . ';');
					$config['model']->{$config['codInfo']} = $v;
				}

				$inputOptions = [
					'class' => $class,
					'disabled' => $disabled,
					'campo' => strtolower($config['glossario']['sgl_cod_info']),
				];

				$objRetorno = $this->field($config['model'], $config['codInfo'], $options)
					->label($config['label'])
					->hint($config['hint'])
					->options($classAE)
				;

				if ($config['glossario']['txt_mascara'])
					$objRetorno->widget(MaskedInput::className(), ['mask' => $config['glossario']['txt_mascara']]);

				$objRetorno->textInput($inputOptions);

				break;

			case 'email':

				if ($config['glossario']['bln_calculavel'] || $config['glossario']['bln_obrigatorio']) {
					$inputOptions = [
						'class' => 'avisoErro'
					];
				} else {
					$inputOptions = [
						//'style' => 'border-color:' . $cor_campo['border_color'] . '; background:' . $cor_campo['background'] . ''
					];
				}

				$options['addon']['append'] = [
					'content' => Html::icon('envelope'),
				];

				$objRetorno = $this->field($config['model'], $config['codInfo'], $options)
					->label($config['label'])
					->hint($config['hint'])
					->options($classAE)
					->input('email')
					->textInput($inputOptions)
				;
				break;

			case 'numeric':


				if ($config['glossario']['bln_calculavel'] || $config['glossario']['bln_obrigatorio']) {
					$inputOptions = [
						'class' => 'avisoErro',
						'style' => 'text-align: right;'
					];
				} else {
					$inputOptions = [
						'style' => 'text-align: right;'
					];
				}

				$inputOptions['for'] = 'formataDecimal';
				$inputOptions['casas'] = $config['glossario']['num_casas_decimais'];
				$objRetorno = $this->field($config['model'], $config['codInfo'], $options)
					->label($config['label'])
					->hint($config['hint'])
					->options($classAE)
					->textInput($inputOptions)
				;

				break;
			case 'money':

				if ($config['glossario']['bln_calculavel'] || $config['glossario']['bln_obrigatorio']) {
					$inputOptions = [
						'class' => 'avisoErro',
						'style' => 'text-align: right;'
					];
				} else {
					$inputOptions = [
						'style' => 'text-align: right;'
					];
				}
				$inputOptions['for'] = 'formataDecimal';
				$inputOptions['casas'] = $config['glossario']['num_casas_decimais'];
				$objRetorno = $this->field($config['model'], $config['codInfo'], $options)
					->label($config['label'])
					->hint($config['hint'])
					->options($classAE)
					->textInput($inputOptions)
				;

				break;

			case 'integer':

				if ($config['glossario']['bln_calculavel'] || $config['glossario']['bln_obrigatorio']) {
					$inputOptions = [
						'class' => 'avisoErro'
					];
				} else {
					$inputOptions = [
						'style' => $estilo_padrao
					];
				}

				$objRetorno = $this->field($config['model'], $config['codInfo'], $options)
					->label($config['label'])
					->hint($config['hint'])
					->options($classAE)
					->widget(MaskedInput::className(), [
						'clientOptions' => [
							'alias' => 'numeric',
							'allowMinus' => false,
							'autoGroup' => true,
							'removeMaskOnSubmit' => true
						]
					])
					->textInput($inputOptions)
				;
				break;

			case 'phone':
				if ($config['glossario']['bln_calculavel'] || $config['glossario']['bln_obrigatorio']) {
					$inputOptions = [
						'class' => 'avisoErro'
					];
				} else {
					$inputOptions = [
						//	'style' => $estilo_padrao
					];
				}

				$options['addon']['append'] = [
					'content' => Html::icon('earphone'),
				];

				$objRetorno = $this->field($config['model'], $config['codInfo'], $options)
					->label($config['label'])
					->hint($config['hint'])
					->options($classAE)
					->widget(MaskedInput::className(), [
						'mask' => ['(99) 9999-9999', '(99) 99999-9999'],
						'options' => [
							'class' => 'form-control text-right'
						],
					])
					->textInput($inputOptions)
				;
				break;

			case 'date-picker':
				if ($config['glossario']['bln_calculavel'] || $config['glossario']['bln_obrigatorio']) {
					$inputOptions = [
						'class' => 'avisoErro'
					];
				} else {
					$inputOptions = [
						'style' => $estilo_padrao
					];
				}

				$options['addon']['append'] = [
					'content' => Html::icon('calendar'),
				];

				$options['options'] = [
					'class' => 'drp-container form-group'
				];

				// Formata a data para exibição
				$this->formatter->nullDisplay = '';
				try {
					$data = $this->formatter->asDate($config['model']->{$config['codInfo']});
				} catch (\yii\base\InvalidParamException $e) {
					$data = '';
				}
				$config['model']->{$config['codInfo']} = $data;

				$objRetorno = $this->field($config['model'], $config['codInfo'], $options)
					->label($config['label'])
					->hint($config['hint'])
					->options($classAE)
					->widget(DateRangePicker::className(), [
						'useWithAddon' => true,
						'convertFormat' => true,
						'pluginOptions' => [
							'timePicker' => false,
							'locale' => ['format' => 'd/m/Y'],
							'singleDatePicker' => true,
							'showDropdowns' => true,
						]
					])
					->textInput($inputOptions)
				;
				break;
		}

		return $objRetorno;
	}

	public static function gerarModal($modalId, $titulo, $html) {

		echo '<div id="modal-ae-' . $modalId . '">';
		Modal::begin([
			'header' => "<h2>{$titulo}</h2>",
			'id' => $modalId,
			'size' => 'modal-lg',
		]);

		echo $html;

		Modal::end();
		echo '</div>';
	}

}
