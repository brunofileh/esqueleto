<?php

namespace projeto\web;

use yii\filters\VerbFilter;
use projeto\filters\AccessControl;
use yii\helpers\Url;

class Controller extends \yii\web\Controller
{
	use \projeto\Atalhos;

	public $titulo;
	public $subTitulo;
	public $breadcrumbs;
	public $activeMenu;
	public $homeLink;
	public $showBreadcrumbs = true;
	// scripts e css registrados no evento init()
	public $js	 = [];
	public $css	 = [];

	public function init()
	{
		parent::init();

		// trait de atalhos \projeto\Atalhos
		$this->configAtalhos();
		$this->registerAssets();
	}
	
	/**
     * Retorna o Módulo.
     */
	public function getModulo() {
		return $this->module->getInfo()['usuario-perfil']['cod_modulo_fk'];
	}

	/**
     * Retorna o diretório da TabParticipacoesSearch do módulo em questão.
     */
	public function getDirTabPartipacoesSearchModulo() {
		$table = '\app\modules\\'. $this->module->getInfo()['usuario-perfil']['modulo_id'] .'\models\TabParticipacoesSearch';
		return $table;
	}
	
	public function setTituloPaginaPrestador()
	{
		if ($tabParticipacoes = $this->session->get('TabParticipacoes')) {
			$this->titulo = "{$tabParticipacoes['dg002']}";
			$this->subTitulo = "{$tabParticipacoes['localizacao']['txt_nome']} - {$tabParticipacoes['localizacao']['sgl_estado_fk']}";
		}
	}

	public function registerAssets()
	{
		foreach ($this->js as $script) {
			$this->view->registerJsFile($script, [
				'position'	 => View::POS_END,
				'depends'	 => [\yii\web\JqueryAsset::className()],
			]);
		}

		foreach ($this->css as $script) {
			$this->view->registerCssFile($script);
		}
	}

	public function json(array $mxDados)
	{
		$this->response->format = \yii\web\Response::FORMAT_JSON;

		// [projeto.ajax] contador de requisições vindo do cliente
		if ($this->request->isPost) {
			if ($intContadorReq = $this->request->post('projetoAjaxIntContadorReq')) {
				$mxDados['projetoAjaxIntContadorReq'] = $intContadorReq;
			}
		} elseif ($this->request->isGet) {
			if ($intContadorReq = $this->request->get('projetoAjaxIntContadorReq')) {
				$mxDados['projetoAjaxIntContadorReq'] = $intContadorReq;
			}
		}

		return $mxDados;
	}

	public function erro($strMensagem)
	{
		return $this->json(array (
			'status' => 'erro',
			'msg'	 => $strMensagem,
		));
	}

	public function render($view, $params = [], $withDefaultBreadcrumbs = true)
	{
		if ($withDefaultBreadcrumbs) {
			$this->defaultBreadcrumbs();
		}
		return parent::render($view, $params);
	}

	public function defaultBreadcrumbs()
	{
		if (!$this->breadcrumbs && $this->module->module && $this->module->module->controller->id != 'login') {

			$infoModulo = $this->module->info;
			$this->breadcrumbs[] = ['label' => $infoModulo['txt_nome'], 'url' => Url::toRoute($infoModulo['txt_url'])];

			if (isset($infoModulo['menu-item'])) {
				if ($this->module->module->controller->action->id != 'index') {
					$this->breadcrumbs[] = [ 'label'	 => $infoModulo['menu-item']['txt_nome'],
						'url'	 => Url::toRoute($infoModulo['menu-item']['txt_url'])];
				} else {
					$this->breadcrumbs[] = [ 'label' => $infoModulo['menu-item']['txt_nome']];
				}
			}

			if ($this->titulo && ( $this->module->module->controller->action->id != 'index')) {
				$this->breadcrumbs[] = [ 'label' => $this->titulo];
			}
		}
	}

	public function actions()
	{
		return [
			'error'		 => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha'	 => [
				'class'				 => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode'	 => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	public function behaviors()
	{
		return [
			'access' => [
				'class'	 => AccessControl::className(),
			],
			'verbs'	 => [
				'class'		 => VerbFilter::className(),
				'actions'	 => [
					'delete' => ['post'],
				],
			]
		];
	}

	public function setDadosUsrMenu(array $dadosUsrMenu)
	{
		$this->_dadosUsrMenu = $dadosUsrMenu;
	}
	
	public function getDadosBusca(array $data)
	{
		$psv = $part = [];
		foreach ($data as $item) {
			$r = explode('-', $item);
			$psv[] = $r[0];
			$part[] = $r[1];
		}
		
		return [$psv, $part];
	}
}
