<?php

namespace projeto\web;

use projeto\helpers\Html;

class ActiveField extends \kartik\form\ActiveField {

	use \projeto\Atalhos;

	public $options = ['class' => 'form-group has-default-2'];
	public $template = "{label}\n{input}\n{error}\n{hint}";
	public $marginRightError = '15px';

	//public $hintOptions		 = ['class' => 'hint-block alert alert-info'];

	protected function setLayoutContainer($type, $css = '', $chk = true) {
		if (!empty($css) && $chk) {
			if ($type == 'hint') {
				$html = Html::tag('div', "{{$type}}", [
					'class' => "col-md-6 hint-{$this->attribute}",
					'style' => [
						'display' => 'none',
						'text-align' => 'justify',
						'padding-left' => '10px',
						'border-left' => '3px solid #CCC',
						'margin-left' => '20px',
					],
				]);
			} else {
				$html = Html::tag('div', "{{$type}}", ['class' => $css]);
			}

			$this->_settings[$type] = $html;
		}
	}

	protected function buildLayoutParts($showLabels, $showErrors) {

		if (!$showErrors) {
			$this->_settings['error'] = '';
		}
		if ($this->skipFormLayout) {
			$this->mergeSettings($showLabels, $showErrors);
			return;
		}
		$inputDivClass = '';
		$errorDivClass = '';
		if ($this->form->hasInputCss()) {
			$offsetDivClass = $this->form->getOffsetCss();
			$inputDivClass = ($this->_offset) ? $offsetDivClass : $this->form->getInputCss();

			// "textarea:g" -> textarea grande, ocupará todo o espaço no layout
			list($tipoCampo, $optCampo) = $this->form->getApresentacaoCampo($this->model, $this->attribute);
			if ($tipoCampo == 'textarea' && $optCampo == 'g') {
				$offsetDivClass = $inputDivClass = 'col-md-12';
			}

			if ($showLabels === false || $showLabels === ActiveForm::SCREEN_READER) {
				$size = ArrayHelper::getValue($this->form->formConfig, 'deviceSize', ActiveForm::SIZE_MEDIUM);
				$errorDivClass = "col-{$size}-{$this->form->fullSpan}";
				$inputDivClass = $errorDivClass;
			} elseif ($this->form->hasOffsetCss()) {
				$errorDivClass = $offsetDivClass;
			}
		}

		$this->setLayoutContainer('input', $inputDivClass);
		$this->setLayoutContainer('error', $errorDivClass, $showErrors);
		$this->setLayoutContainer('hint', $errorDivClass);
		$this->mergeSettings($showLabels, $showErrors);
	}

	public function setTemplate($template) {
		if ($template) {
			$this->template = $template;
		}

		return $this;
	}

	public function options($options = null) {
		
		if ($options) {
			$this->options = str_replace('has-default-2', $options, $this->options);
		}

		return $this;
	}

}
