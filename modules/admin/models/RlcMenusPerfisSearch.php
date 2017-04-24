<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\RlcMenusPerfis;

/**
 * RlcMenusPerfisSearch represents the model behind the search form about `app\modules\admin\models\RlcMenusPerfis`.
 */
class RlcMenusPerfisSearch extends RlcMenusPerfis
{
	
	public $lista_menus;
	/**
     * @inheritdoc
     */ 
    public function rules()
    {

		$rules =  [
            [['lista_menus'], 'safe'],
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
        $query = RlcMenusPerfisSearch::find();

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
            $this->tableName() . '.cod_menu_perfil' => $this->cod_menu_perfil,
            $this->tableName() . '.cod_perfil_fk' => $this->cod_perfil_fk,
            $this->tableName() . '.cod_menu_fk' => $this->cod_menu_fk,
            $this->tableName() . '.dte_inclusao' => $this->dte_inclusao,
            $this->tableName() . '.dte_alteracao' => $this->dte_alteracao,
            $this->tableName() . '.dte_exclusao' => $this->dte_exclusao,
        ]);

        $query->andFilterWhere(['ilike', $this->tableName() . '.txt_login_inclusao', $this->txt_login_inclusao]);

		$query->andWhere($this->tableName().'.dte_exclusao IS NULL');
		
        return $dataProvider;
    }
}
