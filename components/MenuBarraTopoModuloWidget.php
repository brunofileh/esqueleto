<?php

namespace app\components;

use projeto\base\Widget;
use app\modules\admin\models\VisUsuariosPerfisSearch;

class MenuBarraTopoModuloWidget extends Widget
{
	public $modulo_id;
	
	public function init()
	{
		$this->configAtalhos();
		parent::init();
	}
	
	public function run() 
	{
		return $this->render('menu-barra-topo-modulo.php', [
			'modulo_id' => $this->modulo_id,
			'modulos' => VisUsuariosPerfisSearch::getModulosPerfisUsuario(
				$this->user->identity->getId()
			)
		]);
	}
}
