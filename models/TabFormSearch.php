<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TabForm;

/**
 * TabFormSearch represents the model behind the search form about `app\models\TabForm`.
 */
class TabFormSearch extends TabForm
{
    /**
     * @inheritdoc
     */ 
    public function rules()
    {

		$rules =  [
             //exemplo [['txt_nome', 'cod_modulo_fk'], 'required'],
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
        $query = TabFormSearch::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        // if (!$this->validate()) {
        //     // uncomment the following line if you do not want to return any records when validation fails
        //     // $query->where('0=1');
        //     return $dataProvider;
        // }

        $query->andFilterWhere([
            $this->tableName() . '.cod_form' => $this->cod_form,
            $this->tableName() . '.cod_tipo_servico' => $this->cod_tipo_servico,
        ]);

        $query->andFilterWhere(['ilike', $this->tableName() . '.dsc_form', $this->dsc_form])
            ->andFilterWhere(['ilike', $this->tableName() . '.dsc_det_form', $this->dsc_det_form])
            ->andFilterWhere(['ilike', $this->tableName() . '.sgl_form', $this->sgl_form]);

		// $query->andWhere($this->tableName().'.dte_exclusao IS NULL');
		
        return $dataProvider;
    }
}
