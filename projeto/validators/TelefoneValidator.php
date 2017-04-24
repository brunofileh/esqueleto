<?php

namespace projeto\validators;

use yii\helpers\Json;

class TelefoneValidator extends \yii\validators\Validator
{
	public function init()
	{
		parent::init();
		if ($this->message === null) {
			$this->message = 'Número de telefone inválido. Formatos permitidos: (99) 9999-9999 | (99) 99999-9999';
		}
	}

	protected function validateValue($value)
	{
		$valid = true;
		
		$v = substr(preg_replace('/[^0-9]/', '', $value), 0, 2);
		foreach (range(1, 9) as $i) {
			$n = str_repeat($i, 8);
			if ($v == $n || $v == "$n$i") {
				$valid = false;
				break;
			}
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
		return 'projeto.validation.telefone(value, messages, ' . Json::encode($options) . ');';
	}
}