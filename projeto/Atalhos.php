<?php

namespace projeto;

trait Atalhos
{
	public $app;
	public $request;
	public $response;
	public $isPost;
	public $isGet;
	public $isAjax;
	public $isPjax;
	public $session;
	public $cache;
	public $db;
	public $formatter;
	public $user;
	public $mailer;

	public function configAtalhos()
	{
		$this->app = \Yii::$app;
		
		$this->user = $this->app->user;
		$this->request = $this->app->request;
		$this->response = $this->app->response;
		$this->session = $this->app->session;
		$this->cache = $this->app->cache;
		$this->db = $this->app->db;
		$this->formatter = $this->app->formatter;
		$this->mailer = $this->app->mailer;
		
		$this->isPost = $this->request->isPost;
		$this->isGet = $this->request->isGet;
		$this->isAjax = $this->request->isAjax;
		$this->isPjax = $this->request->isPjax;
	}

	public function d($var)
	{
		\projeto\Util::d($var);
	}
	
	public function dd($var)
	{
		\projeto\Util::dd($var);
	}
}
