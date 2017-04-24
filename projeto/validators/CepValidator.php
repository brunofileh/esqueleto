<?php

namespace projeto\validators;

use yii\helpers\Json;

class CepValidator extends \yii\validators\Validator
{
	public function init()
	{
		parent::init();
		if ($this->message === null) {
			$this->message = 'Número do CEP inválido. Formato permitido: 99999-999';
		}
	}

	protected function validateValue($value)
	{
		$valid = true;
		
		$v = preg_replace('/[^0-9]/', '', $value);
		
		if($v==8){
			$valid = false;
		}
		

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
		return 'projeto.validation.cep(value, messages, ' . Json::encode($options) . ');';
	}
}