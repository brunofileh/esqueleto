<?php

namespace app\models\base;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TabModeloDocs;

/**
 * TabModeloDocsSearch represents the model behind the search form about `app\models\TabModeloDocs`.
 */
class TabModeloDocsSearch extends TabModeloDocs
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
        $query = TabModeloDocsSearch::find();

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
            $this->tableName() . '.cod_modelo_doc' => $this->cod_modelo_doc,
            $this->tableName() . '.modulo_fk' => $this->modulo_fk,
            $this->tableName() . '.cabecalho_fk' => $this->cabecalho_fk,
            $this->tableName() . '.rodape_fk' => $this->rodape_fk,
            $this->tableName() . '.tipo_modelo_documento_fk' => $this->tipo_modelo_documento_fk,
            $this->tableName() . '.finalidade_fk' => $this->finalidade_fk,
            $this->tableName() . '.dte_inclusao' => $this->dte_inclusao,
            $this->tableName() . '.dte_alteracao' => $this->dte_alteracao,
            $this->tableName() . '.dte_exclusao' => $this->dte_exclusao,
        ]);

        $query->andFilterWhere(['ilike', $this->tableName() . '.sgl_id', $this->sgl_id])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_descricao', $this->txt_descricao])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_conteudo', $this->txt_conteudo])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_login_inclusao', $this->txt_login_inclusao]);

        $query->andWhere($this->tableName().'.dte_exclusao IS NULL');
        
        return $dataProvider;
    }
}