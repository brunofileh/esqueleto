<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TabBlocoInfo;

/**
 * TabBlocoInfoSearch represents the model behind the search form about `app\models\TabBlocoInfo`.
 */
class TabBlocoInfoSearch extends TabBlocoInfo
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
        $query = TabBlocoInfoSearch::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->andFilterWhere([
            $this->tableName() . '.cod_bloco_info' => $this->cod_bloco_info,
            $this->tableName() . '.num_ordem_bloco' => $this->num_ordem_bloco,
            $this->tableName() . '.servico_fk' => $this->servico_fk,
            $this->tableName() . '.dte_inclusao' => $this->dte_inclusao, 
            $this->tableName() . '.dte_alteracao' => $this->dte_alteracao, 
            $this->tableName() . '.dte_exclusao' => $this->dte_exclusao, 
            $this->tableName() . '.fk_form' => $this->fk_form, 
        ]);

        $query->andFilterWhere(['ilike', $this->tableName() . '.dsc_titulo_bloco', $this->dsc_titulo_bloco])
            ->andFilterWhere(['ilike', $this->tableName() . '.sgl_id', $this->sgl_id])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_login_inclusao', $this->txt_login_inclusao])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_icone', $this->txt_icone]);

		$query->andWhere($this->tableName() . '.dte_exclusao IS NULL');
        $query->orderBy('num_ordem_bloco');
		
        return $dataProvider;
    }
}
