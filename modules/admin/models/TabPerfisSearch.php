<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\TabPerfis;
use yii\helpers\ArrayHelper;

/**
 * TabPerfisSearch represents the model behind the search form about `app\modules\admin\models\TabPerfis`.
 */
class TabPerfisSearch extends TabPerfis
{
    const SCENARIO_RESTRICAO = 'restricao';
    /**
     * @inheritdoc
     */ 
    public function rules()
    {

		$rules =  [
             //exemplo [['txt_nome', 'cod_modulo_fk'], 'required'],
            [['cod_perfil'], 'required', 'on' => self::SCENARIO_RESTRICAO],
            [['cod_perfil', 'dte_inclusao', 'dte_alteracao', 'dte_exclusao'], 'safe'],
        ];
		
		return array_merge($rules, parent::rules());
    }
	
	/**
    * @inheritdoc
    */
	public function attributeLabels()
    {

		$labels = [
            //exemplo 'campo' => 'label',         
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
        $query = TabPerfisSearch::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

       /* if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }*/

        $query->andFilterWhere([
            $this->tableName() . '.cod_perfil' => $this->cod_perfil,
            $this->tableName() . '.cod_modulo_fk' => $this->cod_modulo_fk,
            $this->tableName() . '.dte_inclusao' => $this->dte_inclusao,
            $this->tableName() . '.dte_alteracao' => $this->dte_alteracao,
            $this->tableName() . '.dte_exclusao' => $this->dte_exclusao,
        ]);

        $query->andFilterWhere(['ilike', $this->tableName() . '.txt_nome', $this->txt_nome])
            ->andFilterWhere(['ilike', $this->tableName() . '.dsc_perfil', $this->dsc_perfil])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_login_inclusao', $this->txt_login_inclusao]);

		$query->andWhere($this->tableName().'.dte_exclusao IS NULL');
		
        return $dataProvider;
    }
	
	/**
	 * Creates model with search query applied
	 *
	 * @param integer $cod_modulo
	 *
	 * @return Model
	 */
	public static function perfisPorModulo($cod_modulo)
	{		
		$dados = TabPerfisSearch::find()
			->where(['=', 'cod_modulo_fk', $cod_modulo])
			->andWhere('dte_exclusao IS NULL')
			->orderBy('dsc_perfil')->asArray()->all();
	
		$arr = ArrayHelper::map($dados, 'cod_perfil', 'txt_nome');
		return $arr;
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
            ->select($atributo)
            ->where('cod_modulo_fk=:cod_modulo_fk', [':cod_modulo_fk' => $cod_modulo])
            ->andWhere(['is', self::tableName() . '.dte_exclusao', null])
            ->orderBy(self::tableName() . ".$atributo")
            ->asArray()
            ->all();

        foreach ($dados as $key => $atributo_valor) {
            $arr[] = [ 'value' => $atributo_valor[$atributo], 'text' => $atributo_valor[$atributo]];
        }
        $arr = ArrayHelper::map($arr, 'value', 'text');
        return $arr;
    }
	
	/**
	 * Método que retorna a descrição do perfil
	 * @return String
	 */
	public static function getDescricaoPerfilUsuarioPrestador($cod_usuario)
	{
        $dados = RlcUsuariosPerfisSearch::find()
            ->select([TabPerfisSearch::tableName() . '.txt_nome'])
            ->innerJoin(TabPerfisSearch::tableName(), RlcUsuariosPerfisSearch::tableName() . '.cod_perfil_fk = ' . TabPerfisSearch::tableName() . '.cod_perfil')
            ->where(['=', RlcUsuariosPerfisSearch::tableName() . '.cod_usuario_fk', $cod_usuario])
            ->andWhere(TabPerfisSearch::tableName() . '.dte_exclusao IS NULL')
            ->andWhere(RlcUsuariosPerfisSearch::tableName() . '.dte_exclusao IS NULL')
            ->andwhere(['>', TabPerfisSearch::tableName() . '.txt_perfil_prestador', '0'])
            ->asArray()
            ->one();
        
        return $dados['txt_nome'];
    }

	/**
	 * Método que retorna os perfis de usuários prestadores
	 * @return $model
	 */
	public static function getPerfisUsuariosPrestadores($cod_prestador = null, $cod_modulo)
	{
        $dados = TabPerfisSearch::find()
            ->select([TabPerfisSearch::tableName() . '.cod_perfil', TabPerfisSearch::tableName() . '.txt_nome'])
            ->innerJoin(RlcUsuariosPerfisSearch::tableName(), RlcUsuariosPerfisSearch::tableName() . '.cod_perfil_fk = ' . TabPerfisSearch::tableName() . '.cod_perfil')
            ->innerJoin(TabUsuariosPrestadoresSearch::tableName(), RlcUsuariosPerfisSearch::tableName() . '.cod_usuario_fk = ' . TabUsuariosPrestadoresSearch::tableName() . '.cod_usuario')
            ->where(['=', TabPerfisSearch::tableName() . '.cod_modulo_fk', $cod_modulo])
            ->andwhere(['>', TabPerfisSearch::tableName() . '.txt_perfil_prestador', '0'])
            ->andWhere(TabUsuariosPrestadoresSearch::tableName() . '.dte_exclusao IS NULL')
            ->andWhere(TabPerfisSearch::tableName() . '.dte_exclusao IS NULL')
            ->andWhere(RlcUsuariosPerfisSearch::tableName() . '.dte_exclusao IS NULL')
            ->orderBy(TabPerfisSearch::tableName() . '.txt_nome');
        
		if ($cod_prestador) {
			$dados->andWhere(['=', TabUsuariosPrestadoresSearch::tableName() . '.cod_prestador_fk', $cod_prestador]);
		}        
        
        $arr = ArrayHelper::map($dados->all(), 'cod_perfil', 'txt_nome');
        
        return $arr;
    }
	
	public static function buscaPerfisModuloGridUsuarios()
	{
		$perfil = (\yii::$app->session->get('perfil-modulo')) ? \yii::$app->session->get('perfil-modulo') : array();
		
		$dataProvider = new \yii\data\ArrayDataProvider([
			'id' => 'grid-perfil-modulo',
			'allModels' => $perfil,
			'sort' => false,
			'pagination' => ['pageSize' => 10],
		]);


		return $dataProvider;
	}
    
}
