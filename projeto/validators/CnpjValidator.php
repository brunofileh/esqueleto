<?php

namespace projeto\validators;

use yii\helpers\Json;

class CnpjValidator extends \yii\validators\Validator
{
	public function init()
	{
		parent::init();
		if ($this->message === null) {
			$this->message = 'Número do CNPJ inválido. Formato permitido: 99.999.999/9999-99';
		}
	}

	protected function validateValue($value)
	{
		$valid = true;
		
			$cnpj = preg_replace('/[^0-9]/', '', (string) $value);
			
			// Valida tamanho
			if (strlen($cnpj) != 14)
				$valid = false;
			// Valida primeiro dígito verificador
			for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
			{
				$soma += $cnpj{$i} * $j;
				$j = ($j == 2) ? 9 : $j - 1;
			}
			$resto = $soma % 11;
			if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
				$valid = false;
			// Valida segundo dígito verificador
			for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
			{
				$soma += $cnpj{$i} * $j;
				$j = ($j == 2) ? 9 : $j - 1;
			}
			$resto = $soma % 11;
			$valid = $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
		
		return ($valid) ? [] : [$this->message, []];
	}
	
	public function clientValidateAttribute($object, $attribute, $view)
	{
		$options = [
			'message' => $this->message,
		];

		if ($this->skipOnEmpty) {
			$options['skipOnEmpty'] = 1;
		}

		ValidationAsset::register($view);
		return 'projeto.validation.cnpj(value, messages, ' . Json::encode($options) . ');';
	}
}