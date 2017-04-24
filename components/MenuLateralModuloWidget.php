<?php

namespace app\components;

use Yii;
use projeto\helpers\Html;

use projeto\base\Widget;

use app\modules\admin\models\TabModulosSearch;
use app\modules\admin\models\TabMenusSearch;
use app\modules\admin\models\VisUsuariosPerfis;

class MenuLateralModuloWidget extends Widget
{
	public $modulo_id;
	
	public function init()
	{
		parent::init();
	}
	
	public function run() 
	{
		$modulo = TabModulosSearch::getInfo($this->modulo_id);
		$getData = function () use ($modulo) {
			return VisUsuariosPerfis::findOneAsArray([
				'cod_modulo_fk' => $modulo['cod_modulo'], 
				'cod_usuario_fk' => $this->user->identity->getId(),
			]);
		};
		
		if (Yii::$app->params['habilitar-cache-global']) {
			$cacheKey = [
				Yii::$app->session->id, 
				'modulo-usuario',
				'cod_modulo_fk', $modulo['cod_modulo'], 
				'cod_usuario_fk', $this->user->identity->getId(),
			];
			if (($data = Yii::$app->cache->get($cacheKey)) === false) {
				$data = $getData();
				Yii::$app->cache->set($cacheKey, $data);
			}
			$params = $data;
		}
		else {
			$params = $getData();
		}
		
		
		return $this->render('menu-lateral-modulo.php', [
			'menus' => TabMenusSearch::montarMenuCache($params, Yii::$app->controller->activeMenu),
			'modulo_id' => $this->modulo_id
		]);
	}
}
