<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\VisPerfisFuncionalidadesAcoesModulos;


/**
 * VisPerfisFuncionalidadesAcoesModulosSearch represents the model behind the search form about `app\modules\admin\models\VisPerfisFuncionalidadesAcoesModulos`.
 */
class VisPerfisFuncionalidadesAcoesModulosSearch extends VisPerfisFuncionalidadesAcoesModulos
{

	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();

	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public static function searchPermissoes( $perfil, $usuario )
	{
		$permissoes	 = [];
		$dados		 = VisPerfisFuncionalidadesAcoesModulos::findAllAsArray( [
				'cod_perfil_fk'	 => $perfil,
				'cod_usuario_fk' => $usuario
			] );

		foreach ($dados as $value)
		{
			$permissoes[$value['nome_funcionalidade']][] = $value['nome_acao'];
		}

		return $permissoes;

	}

	public static function getUsrFuncionalidadesAcoesModulos( array $params )
	{
		$getData = function () use ($params)
		{
			return VisPerfisFuncionalidadesAcoesModulosSearch::findOneAsArray( [
					'UPPER(modulo_id)'			 => strtoupper( $params['moduloID'] ),
					'cod_usuario_fk'			 => $params['codUsuario'],
					'UPPER(nome_funcionalidade)' => strtoupper( $params['controllerID'] ),
					'UPPER(nome_acao)'			 => strtoupper( $params['actionID'] ),
				] );
		};


		if (Yii::$app->params['habilitar-cache-global'])
		{
			$cacheKey = [
				Yii::$app->session->id,
				'cod_modulo_fk', $params['moduloID'],
				'cod_perfil_fk', $params['codUsuario'],
				'controller', $params['controllerID'],
				'action', $params['actionID'],
			];

			if (($data = Yii::$app->cache->get( $cacheKey )) === false)
			{
				$data = $getData();
				Yii::$app->cache->set( $cacheKey, $data );
			}
			else
			{
				$data = $getData();
			}

			return $data;
		}
		else
		{
			$data = $getData();
		}

		return $data;

	}

}
