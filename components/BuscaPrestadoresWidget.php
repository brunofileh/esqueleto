<?php

namespace app\components;

use Yii;
use projeto\base\Widget;
use app\models\VisConsultaPrestadoresSearch;
use app\modules\admin\models\TabModulosSearch;

class BuscaPrestadoresWidget extends Widget
{
	public $id_modulo;
	public $params = []; // parâmetros da busca (queryString params)
	public $action; // action do form
	public $nomeAcao = []; // Ações cadastradas para a funcionalidade busca de prestadores
	public $arrConfigCampos = []; // configurações dos campos do form
	
	public function init()
	{
		parent::init();
		
		if (!is_array($this->nomeAcao)) {
			$this->nomeAcao = [$this->nomeAcao];
		}
	}
	
	public function run()
	{
		$model = new VisConsultaPrestadoresSearch();
		$params = Yii::$app->request->queryParams;
				
		if (!isset($params['VisConsultaPrestadoresSearch'])) {
			$params['VisConsultaPrestadoresSearch'] = [];
		}
		
		$cod_modulo = ($this->id_modulo != null) 
			? TabModulosSearch::find()->where(['id' => $this->id_modulo])->one()->cod_modulo 
			: null
		;

		if (empty($params['VisConsultaPrestadoresSearch'])) {
			$params['VisConsultaPrestadoresSearch']['ano_ref'] = [$this->app->params['ano-ref']];
			if ($cod_modulo) {
				$params['VisConsultaPrestadoresSearch']['cod_modulo'] = [$cod_modulo];
			}
		}

		$pageSize = 20; // default
		$perPage = isset(Yii::$app->request->queryParams['per-page']) 
			? Yii::$app->request->queryParams['per-page']
			: null
		;
		
		if (in_array($perPage, [-1, 10, 20, 50, 100])) {
			$pageSize = $perPage;
		}
		$dataProvider = $model->search($params);
		$dataProvider->pagination->pageSize = $pageSize;
		
		return $this->render('busca-prestadores', [
			'model' => $model,
			'dataProvider' => $dataProvider,
			'cod_modulo' => $cod_modulo,
			'action' => $this->action,
			'nomeAcao' => $this->nomeAcao,
			'pageSize' => $pageSize,
			'arrConfigCampos' => $this->arrConfigCampos,
		]);
	}
}
