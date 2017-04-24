<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\TabFuncionalidades;
use yii\helpers\ArrayHelper;

/**
 * TabFuncionalidadesSearch represents the model behind the search form about `app\modules\admin\models\TabFuncionalidades`.
 */
class TabFuncionalidadesSearch extends TabFuncionalidades
{

	public $lista_menu;
	public $lista_perfil;
	public $lista_acao;
	
    /**
     * @inheritdoc
     */ 
    public function rules()
    {

		$rules =  [
			[['lista_menu'], 'safe'],
        ];
		
		return array_merge($rules, parent::rules());
    }
	
	/**
    * @inheritdoc
    */
	public function attributeLabels()
    {

		$labels = [
            'lista_acao' => 'Ações',
			'lista_menu' => 'Menu',
        ];
		
		return array_merge( parent::attributeLabels(), $labels);
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
        $query = TabFuncionalidades::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

        $query->andFilterWhere([
            $this->tableName() . '.cod_funcionalidade' => $this->cod_funcionalidade,
            $this->tableName() . '.dte_inclusao' => $this->dte_inclusao,
            $this->tableName() . '.dte_alteracao' => $this->dte_alteracao,
        ]);

        $query->andFilterWhere(['ilike', $this->tableName() . '.txt_nome', $this->txt_nome])
            ->andFilterWhere(['ilike', $this->tableName() . '.dsc_funcionalidade', $this->dsc_funcionalidade])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_login_inclusao', $this->txt_login_inclusao]);

		$query->andWhere($this->tableName() . '.dte_exclusao is null');
		
        return $dataProvider;
    }
	
    /**
     * Creates data provider instance with searchTabFuncionalidades query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchTabFuncionalidades($params)
    {
        $query = TabFuncionalidadesSearch::find();
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
        $this->load($params);
		
		$query->distinct(true);
		$query->joinWith(['rlcPerfisFuncionalidadesAcoes', 'rlcPerfisFuncionalidadesAcoes.tabPerfis']);
		
		$query->andWhere($this->tableName().'.dte_exclusao is null');
		$query->andWhere('cod_modulo_fk = '.$params['cod_modulo'].' OR cod_funcionalidade_fk is null');
		
        $query->andFilterWhere([
            ''.$this->tableName().'.cod_funcionalidade' => $this->cod_funcionalidade,
            ''.$this->tableName().'.dte_inclusao' => $this->dte_inclusao,
            ''.$this->tableName().'.dte_alteracao' => $this->dte_alteracao,			
        ]);
		
        $query->andFilterWhere(['ilike', $this->tableName().'.txt_nome', $this->txt_nome])
            ->andFilterWhere(['ilike', $this->tableName().'.dsc_funcionalidade', $this->dsc_funcionalidade])
            ->andFilterWhere(['ilike', $this->tableName().'.txt_login_inclusao', $this->txt_login_inclusao]);
		
		if (!isset($_GET["sort"])) {
			$query->orderBy('cod_funcionalidade desc');
		}
		
        return $dataProvider;
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
        } else
            return null;
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
            ->join('LEFT JOIN', RlcPerfisFuncionalidadesAcoes::tableName(), self::tableName() . '.cod_funcionalidade = ' . RlcPerfisFuncionalidadesAcoes::tableName() . '.cod_funcionalidade_fk')
            ->join('LEFT JOIN', TabPerfis::tableName(), RlcPerfisFuncionalidadesAcoes::tableName() . '.cod_perfil_fk = ' . TabPerfis::tableName() . '.cod_perfil')
            ->where('cod_modulo_fk=:cod_modulo_fk', [':cod_modulo_fk' => $cod_modulo])
           // ->andWhere(['is', self::tableName() . '.dte_exclusao', null])
            ->orderBy(self::tableName() . ".$atributo")
            ->distinct()
            ->asArray()
            ->all();

        foreach ($dados as $key => $atributo_valor) {
            $arr[] = [ 'value' => $atributo_valor[$atributo], 'text' => $atributo_valor[$atributo]];
        }
        $arr = ArrayHelper::map($arr, 'value', 'text');
        return $arr;
    }
}
