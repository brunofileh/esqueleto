<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\TabMenus;
use app\modules\admin\models\VisMenusPerfis;
use yii\helpers\ArrayHelper;

/**
 * TabMenusSearch represents the model behind the search form about `app\modules\admin\models\TabMenus`.
 */
class TabMenusSearch extends TabMenus
{

	public $lista_perfil;
	public $cod_modulo_fk;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[ ['txt_nome' , 'lista_perfil'] , 'required'] ,
			[ ['num_ordem' , 'num_nivel' , 'cod_perfil_funcionalidade_acao_fk' , 'cod_menu_fk'] , 'integer'] ,
			[ ['dte_inclusao' , 'dte_alteracao' , 'dte_exclusao' , 'cod_modulo_fk'] , 'safe'] ,
			[ ['txt_nome'] , 'string' , 'max' => 80] ,
			[ ['dsc_menu' , 'txt_login_inclusao'] , 'string' , 'max' => 150] ,
			[ ['txt_url' , 'txt_imagem'] , 'string' , 'max' => 100]
		];

	}

	/**
	 * @inheritdoc
	 */
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
	public function search($params)
	{
		$query = TabMenusSearch::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		if (!$this->validate()){
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		$query->andFilterWhere( [
			$this->tableName() . '.cod_menu'							 => $this->cod_menu ,
			$this->tableName() . '.num_ordem'							 => $this->num_ordem ,
			$this->tableName() . '.num_nivel'							 => $this->num_nivel ,
			$this->tableName() . '.cod_perfil_funcionalidade_acao_fk'	 => $this->cod_perfil_funcionalidade_acao_fk ,
			$this->tableName() . '.cod_menu_fk'							 => $this->cod_menu_fk ,
			$this->tableName() . '.dte_inclusao'						 => $this->dte_inclusao ,
			$this->tableName() . '.dte_alteracao'						 => $this->dte_alteracao ,
		] );

		$query->andFilterWhere( ['like' , $this->tableName() . '.txt_nome' , $this->txt_nome] )
			->andFilterWhere( ['like' , $this->tableName() . '.dsc_menu' , $this->dsc_menu] )
			->andFilterWhere( ['like' , $this->tableName() . '.txt_url' , $this->txt_url] )
			->andFilterWhere( ['like' , $this->tableName() . '.txt_imagem' , $this->txt_imagem] )
			->andFilterWhere( ['like' , $this->tableName() . '.txt_login_inclusao' , $this->txt_login_inclusao] );

		$query->andWhere( $this->tableName() . '.dte_exclusao IS NULL' );

		return $dataProvider;

	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function searchMenuModulo($params)
	{
		$query = TabMenusSearch::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		/* if (!$this->validate())
		  {
		  // uncomment the following line if you do not want to return any records when validation fails
		  // $query->where('0=1');
		  return $dataProvider;
		  }
		 */
		$query->joinWith(['rlcMenusPerfis' , 'rlcMenusPerfis.tabPerfis']);
		$codPFA = 'cod_perfil_funcionalidade_acao_fk';
		$query->andFilterWhere([
			$this->tableName() . '.cod_menu'		=> $this->cod_menu,
			$this->tableName() . '.num_ordem'		=> $this->num_ordem,
			$this->tableName() . '.num_nivel'		=> $this->num_nivel,
			$this->tableName() . ".{$codPFA}"		=> $this->$codPFA,
			$this->tableName() . '.cod_menu_fk'		=> $this->cod_menu_fk,
			$this->tableName() . '.dte_inclusao'	=> $this->dte_inclusao,
			$this->tableName() . '.dte_alteracao'	=> $this->dte_alteracao,
			'cod_modulo_fk'							=> $this->cod_modulo_fk,
		]);

		$query->andFilterWhere(['like', $this->tableName() . '.txt_nome', $this->txt_nome])
			->andFilterWhere(['like', $this->tableName() . '.dsc_menu', $this->dsc_menu])
			->andFilterWhere(['like', $this->tableName() . '.txt_url', $this->txt_url])
			->andFilterWhere(['like', $this->tableName() . '.txt_imagem', $this->txt_imagem])
			->andFilterWhere(['like', $this->tableName() . '.txt_login_inclusao', $this->txt_login_inclusao]);

		$query->andWhere($this->tableName() . '.dte_exclusao IS NULL');
		// $query->orderBy('num_ordem');

		return $dataProvider;

	}

	/* Monta array com os menus de acordo com o perfil do usuario
	 *
	 * @param VisUsuariosPerfis $params
	 *
	 * @return array
	 */

	public static function montarMenuCache($params, $active = null)
	{
		$params = (array) $params;
		if (Yii::$app->params['habilitar-cache-global']) {
			$userID		 = Yii::$app->user->identity->getId();
			$cacheKey	 = [
				Yii::$app->session->id,
				'cod_modulo_fk'	 => $params['cod_modulo_fk'],
				'cod_perfil_fk'	 => $params['cod_perfil_fk'],
				'cod_usuario_fk' => $userID,
			];

			if (($data = Yii::$app->cache->get($cacheKey)) === false) {
				$data = static::montarMenu($params, null, $active);
				Yii::$app->cache->set($cacheKey, $data);
			}
		} else {
			$data = static::montarMenu($params, null, $active);
		}

		return $data;
	}

	public static function montarMenu($params, $filho = null, $active = null)
	{
		$menuGeral	 = [];
		$userID		 = Yii::$app->user->identity->getId();
		$menus		 = VisMenusPerfis::find()->where([
			'cod_modulo_fk'		 => $params['cod_modulo_fk'],
			'cod_perfil_fk'		 => $params['cod_perfil_fk'],
			'cod_usuario_fk'	 => $userID,
			'cod_menu_pai_fk'	 => $filho,
		])->orderBy(['num_ordem' => SORT_ASC])->asArray()->all();

		foreach ($menus as $key => $menu) {
			
			$menuFilho = static::montarMenu($params, $menu['cod_menu_fk'], $active);

			if ($menuFilho) {
				$menuGeral[$key] = [
					'label'		 => $menu['nome_menu'],
					'url'		 => [$menu['txt_url']],
					'icon'		 => $menu['txt_imagem'],
					'items'		 => $menuFilho,
				];
			} else {
				$menuGeral[$key] = [
					'label'		 => $menu['nome_menu'],
					'url'		 => [$menu['txt_url']],
					'icon'		 => $menu['txt_imagem'],
				];
				
				if(strtoupper($active) == strtoupper($menu['nome_menu'])) {
					$menuGeral[$key]['active'] = $active;
				}
//				$menuGeral[$key]['active'] = true; // abre todos os menus
			}
		}

		return $menuGeral;
	}

	/**
	 * Creates model with search query applied
	 *
	 * @param integer $cod_modulo
	 *
	 * @return Model
	 */
	public static function menusPorModulo($cod_modulo, $cod_funcionalidade = null)
	{
		$dados = TabMenusSearch::find()
			->distinct(true)
			->joinWith(['rlcMenusPerfis' , 'rlcMenusPerfis.tabPerfis'])
			->where(['=', 'cod_modulo_fk', $cod_modulo])
			->andWhere(TabMenusSearch::tableName() . '.dte_exclusao IS NULL')
			->andWhere(TabMenusSearch::tableName() . '.cod_perfil_funcionalidade_acao_fk IS NULL')
			->orderBy(['dsc_menu' => SORT_ASC]);

		if ($cod_funcionalidade) {
			$dados->orWhere([
				'=', 
				TabMenusSearch::tableName() . '.cod_perfil_funcionalidade_acao_fk', 
				$cod_funcionalidade
			]);
		}

		return $dados->all();
	}
    
    /**
     * Método para retorna a descrição no combo de pesquisa na grid
     * @param String $atributo Atributo que deseja recuperar os dados
     * @param string $valor Valor do atributo para pesquisa
     * @return String
     */
    public static function getTextoPorAtributo($atributo, $valor)
    {
        if ($atributo) {
            $object = (Object) self::find()
                ->where("$atributo=:atributo", [':atributo' => $valor])
                ->asArray()
                ->one();

            return $object->$atributo;
        } else {
            return null;
		}
    }

    /**
     * Retorna uma lista simples com o valor passado como atributo
     * @param String $atributo Atributo que deseja recuperar os dados
     * @return Array
     */
    public static function getListaPorAtributo($atributo, $cod_modulo)
    {
		$arr = [];
		
        $dados = self::find()
            ->select(self::tableName() . ".$atributo")
            ->join('LEFT JOIN', RlcMenusPerfis::tableName(), self::tableName() . '.cod_menu = ' . RlcMenusPerfis::tableName() . '.cod_menu_fk')
            ->join('LEFT JOIN', TabPerfis::tableName(), RlcMenusPerfis::tableName() . '.cod_perfil_fk = ' . TabPerfis::tableName() . '.cod_perfil')
            ->where('cod_modulo_fk=:cod_modulo_fk', [':cod_modulo_fk' => $cod_modulo])
            ->andWhere(['is', self::tableName() . '.dte_exclusao', null])
            ->orderBy(self::tableName() . ".$atributo")
            ->asArray()
            ->all();

        foreach ($dados as $key => $atributo_valor) {
            $arr[] = [ 'value' => $atributo_valor[$atributo], 'text' => $atributo_valor[$atributo]];
        }
        
        return ArrayHelper::map($arr, 'value', 'text');
    }
}