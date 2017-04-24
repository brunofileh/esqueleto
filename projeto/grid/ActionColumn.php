<?php

namespace projeto\grid;

use Yii;
use yii\helpers\Html;
use projeto\Atalhos;

class ActionColumn extends \yii\grid\ActionColumn
{
	use Atalhos;
	
	public $header = 'Ações';

	public $buttonOptions = [
		'data-toggle'	 => 'tooltip',
		'data-placement' => 'top',
	];

	/**
	 * Inicializa os atalhos
	 */
	public function init()
	{
		parent::init();

		$this->configAtalhos();
		$this->initDefaultButtons();
		$this->verificaPermissao();
	}

	/**
	 * Initializes the default button rendering callbacks.
	 */
	protected function initDefaultButtons()
	{
		if (!isset($this->buttons['view'])) {
			$this->buttons['view'] = function ($url, $model, $key) {
				$options = array_merge([
					'title'		 => 'Exibir',
					'aria-label' => Yii::t('yii', 'View'),
					'data-pjax'	 => '0',
					], $this->buttonOptions);
				return Html::a('<span class="fa fa-search-plus"></span>', $url, $options);
			};
		}

		if (!isset($this->buttons['admin'])) {
			$this->buttons['admin'] = function ($url, $model, $key) {
				$options = array_merge([
					'title'		 => 'Alterar',
					'aria-label' => 'Alterar',
					'data-pjax'	 => '0',
					], $this->buttonOptions);

				return Html::a('<span class="fa fa-edit"></span>', $url, $options);
			};
		} else if (!isset($this->buttons['update'])) {
			$this->buttons['update'] = function ($url, $model, $key) {
				$options = array_merge([
					'title'		 => 'Alterar',
					'aria-label' => 'Alterar',
					'data-pjax'	 => '0',
					], $this->buttonOptions);
				return Html::a('<span class="fa fa-edit"></span>', $url, $options);
			};
		}


		if (!isset($this->buttons['delete'])) {
			$this->buttons['delete'] = function ($url, $model, $key) {
				$options = array_merge([
					'title'			 => 'Excluir',
					'aria-label'	 => 'Excluir',
					'data-confirm'	 => 'Confirma a exclusão do registro?',
					'data-method'	 => 'post',
					'data-pjax'		 => '0',
					], $this->buttonOptions);
				return Html::a('<span class="fa fa-trash"></span>', $url, $options);
			};
		}

	}

	protected function verificaPermissao()
	{
		$dados = $this->app->controller->module->getInfo();

		if (isset($dados['funcionalidade-acao'])) {

			if (!isset($dados['funcionalidade-acao'][$this->app->controller->id])) {
				throw new \yii\base\InvalidConfigException('Ação não encontrada. Verifique se você cadastrou ações para esta funcionalidade.');
			}

			$permissoes = $dados['funcionalidade-acao'][$this->app->controller->id];
			$template = $this->template;

			foreach ($this->buttons as $key => $button) {
				if (!in_array($key, $permissoes)) {
					$template = str_replace('{' . $key . '}', '', $template);
				}
			}
			
			$this->template = $template;
		}
	}
}
