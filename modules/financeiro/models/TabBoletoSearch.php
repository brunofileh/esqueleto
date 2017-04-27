<?php

namespace app\modules\financeiro\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\financeiro\models\TabBoleto;

/**
 * TabBoletoSearch represents the model behind the search form about `app\modules\financeiro\models\TabBoleto`.
 */
class TabBoletoSearch extends TabBoleto
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
        $query = TabBoletoSearch::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->andFilterWhere([
            $this->tableName() . '.cod_boleto' => $this->cod_boleto,
            $this->tableName() . '.dt_vencimento' => $this->dt_vencimento,
            $this->tableName() . '.ds_valor' => $this->ds_valor,
            $this->tableName() . '.cod_tipo_contrato_fk' => $this->cod_tipo_contrato_fk,
            $this->tableName() . '.dt_inclusao' => $this->dt_inclusao,
            $this->tableName() . '.valor_multa' => $this->valor_multa,
            $this->tableName() . '.multa' => $this->multa,
            $this->tableName() . '.dt_pagamento' => $this->dt_pagamento,
            $this->tableName() . '.advogado' => $this->advogado,
            $this->tableName() . '.valor_pago' => $this->valor_pago,
            $this->tableName() . '.ativo' => $this->ativo,
            $this->tableName() . '.dt_processamento' => $this->dt_processamento,
            $this->tableName() . '.dt_ocorrencia' => $this->dt_ocorrencia,
            $this->tableName() . '.dt_exclusao' => $this->dt_exclusao,
        ]);

        $query->andFilterWhere(['ilike', $this->tableName() . '.nu_documento', $this->nu_documento])
            ->andFilterWhere(['ilike', $this->tableName() . '.nu_doc', $this->nu_doc])
            ->andFilterWhere(['ilike', $this->tableName() . '.nosso_numero', $this->nosso_numero])
            ->andFilterWhere(['ilike', $this->tableName() . '.valor_juros', $this->valor_juros])
            ->andFilterWhere(['ilike', $this->tableName() . '.fic_comp', $this->fic_comp])
            ->andFilterWhere(['ilike', $this->tableName() . '.fic_comp2', $this->fic_comp2])
            ->andFilterWhere(['ilike', $this->tableName() . '.justificativa_exclusao', $this->justificativa_exclusao]);

		$query->andWhere($this->tableName().'.dt_exclusao IS NULL');
		
        return $dataProvider;
    }
}
