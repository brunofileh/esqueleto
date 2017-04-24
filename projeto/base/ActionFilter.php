<?php

namespace projeto\base;

use yii\helpers\Url;
use projeto\Atalhos;
use projeto\Util;

class ActionFilter extends \yii\base\ActionFilter
{
	/**
	 * trait de atalhos
	 */
	use Atalhos;

	public $rules = [];

	/**
	 * Inicializa os atalhos
	 */
	public function init()
	{
		parent::init();
		$this->configAtalhos();
	}

	/**
	 * Este método é executado antes de cada ação 
	 * e verifica se o usuário logado tem permissão
	 * para acessar a ação (método) solicitada
	 */
	public function beforeFilter($action)
	{

		if ($this->user->isGuest && ( (strpos($action->action->id, 'lista')===false || strpos( $action->action->id, 'combo')===false) ) ) {
			if (empty(Url::previous('sessao-expirada'))) {
				Url::remember('', 'sessao-expirada');
			}
	
			parent::beforeFilter($action);

			return false;
		}

		$dte_atual	 = strtotime(date('Y-m-d H:i:s'));
		$dte_final	 = strtotime('+8 hour', strtotime($this->user->identity->dte_sessao));

		if ($this->user->identity->txt_login != 'administrador') {

			if (!$this->user->identity->num_ip) {
				$this->user->identity->ativarInativaSessao(true);
			} else if ($dte_atual >= $dte_final) {
				$this->user->identity->ativarInativaSessao(true);
			} else if ($this->user->identity->num_ip != Util::getClientIP()) {
				$this->user->identity->sessao_ativa = false;
			}
		}

		return parent::beforeFilter($action);
	}


}