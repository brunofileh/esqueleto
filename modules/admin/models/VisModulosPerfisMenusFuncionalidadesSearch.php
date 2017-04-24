<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use app\modules\admin\models\VisModulosPerfisMenusFuncionalidades;

/**
 * This is the model class for table "acesso.vis_modulos_perfis_menus_funcionalidades".
 *
 */
class VisModulosPerfisMenusFuncionalidadesSearch extends VisModulosPerfisMenusFuncionalidades
{
	
   	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();

	}    	       
}
