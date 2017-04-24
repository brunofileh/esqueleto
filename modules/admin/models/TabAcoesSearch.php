<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\TabAcoes;
use yii\helpers\ArrayHelper;

/**
 * TabAcoesSearch represents the model behind the search form about `app\modules\admin\models\TabAcoes`.
 */
class TabAcoesSearch extends TabAcoes
{
    /**
     * @inheritdoc
     */ 
    public function rules()
    {
        return [
            [['cod_acao'], 'integer'],
            [['txt_nome', 'dsc_acao', 'txt_login_inclusao', 'dte_inclusao', 'dte_alteracao'], 'safe'],
        ];
    }
	
	/**
    * @inheritdoc
    */
	public function attributeLabels()
    {
		$labelsParent = parent::attributeLabels();
		
		$label = [
            //'campo' => 'label',         
        ];
		
		return array_merge($labelsParent, $label);
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
        $query = TabAcoesSearch::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            $this->tableName() . '.cod_acao' => $this->cod_acao,
            $this->tableName() . '.dte_inclusao' => $this->dte_inclusao,
            $this->tableName() . '.dte_alteracao' => $this->dte_alteracao,
        ]);

        $query->andFilterWhere(['ilike', $this->tableName() . '.txt_nome', $this->txt_nome])
            ->andFilterWhere(['ilike', $this->tableName() . '.dsc_acao', $this->dsc_acao])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_login_inclusao', $this->txt_login_inclusao]);

		$query->andWhere($this->tableName() . '.dte_exclusao IS NULL');
		
		
		if (!isset($_GET["sort"])) {
			$query->orderBy('cod_acao desc');
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
    public static function getListaPorAtributo($atributo)
    {
        $dados = self::find()
            ->select($atributo)
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
}
