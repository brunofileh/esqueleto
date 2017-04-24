<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TabEstados;


/**
 * TabEstadosSearch represents the model behind the search form about `app\models\TabEstados`.
 * 
 * @property TabMunicipiosSearch[] $tabMunicipio
 */
class TabEstadosSearch extends TabEstados
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
            'sgl_estado' => 'UF',         
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
        $query = TabEstadosSearch::find();

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
            $this->tableName() . '.qtd_mun_est' => $this->qtd_mun_est,
            $this->tableName() . '.vlr_taxa_hab_dom' => $this->vlr_taxa_hab_dom,
            $this->tableName() . '.cod_regiao_geografica' => $this->cod_regiao_geografica,
        ]);

        $query->andFilterWhere(['ilike', $this->tableName() . '.sgl_estado', $this->sgl_estado])
            ->andFilterWhere(['ilike', $this->tableName() . '.cod_estado', $this->cod_estado])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_nome', $this->txt_nome])
            ->andFilterWhere(['ilike', $this->tableName() . '.cod_cpt_est', $this->cod_cpt_est]);

		$query->andWhere($this->tableName().'.dte_exclusao IS NULL');
		
        return $dataProvider;
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getTabMunicipios()
    {
        return $this->hasMany(TabMunicipiosSearch::className(), ['sgl_estado_fk' => 'sgl_estado']);
    }
}
