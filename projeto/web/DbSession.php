<?php

namespace projeto\web;

use yii\base\NotSupportedException;

class DbSession extends \yii\web\DbSession
{
	public $alertParam = '__alert';
	
	public function setFlash($key, $value=true, $removeAfterAccess=true)
	{
		if (!in_array($key, ['success', 'info', 'warning', 'danger'])) {
			throw new NotSupportedException("[projeto\web\DbSession::setFlash()] chave não permitida: {$key}");
		}

		parent::setFlash($key, $value, $removeAfterAccess);
	}
	
	public function setAlert(array $params=[])
	{
		if (!isset($params['type'])) {
			$params['type'] = 'success';
		}
		
		if (!in_array($params['type'], ['success', 'info', 'warning', 'error'])) {
			throw new NotSupportedException("[projeto\web\DbSession::setAlert()] chave não permitida: {$params['type']}");
		}

		$_SESSION[$this->alertParam] = $params;
	}

	public function hasAlert()
	{
		return isset($_SESSION[$this->alertParam]);
	}
	
	public function getAlert($defaultValue = null)
    {
		if (isset($_SESSION[$this->alertParam])) {
			$value = $this->get($this->alertParam, $defaultValue);
			$_SESSION[$this->alertParam] = null;
            return $value;
		}
		return $defaultValue;
    }
	
	public function makeAlert(array $params=[], \yii\web\View $view = null)
	{
		$callbackFn = isset($params['callback']) ? $params['callback'] : false;
		$callback = '';
		if ($callbackFn) {
			$callback = ", function (isConfirm) {{$callbackFn}}";
			unset($params['callback']);
		}
		$json = json_encode($params);
		$script = "$(function(){ swal({$json}{$callback});} );";
		
		if ($view) {
			$view->registerJs($script);
		}
		else {
			return $script;
		}
	}
	
	public function setFlashProjeto($key, $acao, $msgExtra='', $removeAfterAccess=true)
	{
            
		if (!($value = \Yii::$app->params["msg-padrao-crud-$key-$acao"])) {
			throw new NotSupportedException("[projeto\web\DbSession::setFlashProjeto()] chave não encontrada: {$key}");
		}

		if (is_string($msgExtra)) {
			$value .= " $msgExtra";
		}
		elseif (is_array($msgExtra)) {
			$value .= ' (' . implode(', ', $msgExtra) . ')';
		}
		
		$this->setFlash($key, $value, $removeAfterAccess);
	}
}
