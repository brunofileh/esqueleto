<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TabAtributos;

/**
 * TabAtributosSearch represents the model behind the search form about `app\models\TabAtributos`.
 */
class TabAtributosSearch extends TabAtributos
{

    const TIPO_OPERACAO_UNIDADE			    = 1; // Tipo de operação da unidade
    const OPT_SIM_NAO					    = 2; // Opções de Sim/Não
    const RLC_SERVICO_PRESTADO			    = 3; // Serviço prestado
    const RLC_ABRANGENCIA_INFORMACAO	    = 4; // Abrangência da informação
    const RLC_NATUREZA_INFORMACAO		    = 5; // Natureza da informação
    const RLC_FAMILIA_INFORMACAO		    = 6; // Família da informação
    const RLC_TIPO_ORIGEM_INFORMACAO	    = 7; // Tipo de origem da informação
    const RLC_TIPO_UNIDADE_INFORMACAO       = 8; // Tipo de unidade da informação
    const RLC_TIPO_APRESENTACAO			    = 9; // Tipo de apresentação do campo
    const RLC_TABELAS_COLETA		    	= 10; // Relação de tabelas da coleta de dados
	const NATUREZA					     	= 12; // Natureza
	const ABRANGENCIA				    	= 13; // Abrangência
	const SITUACAO_PREENCHIMENTO	    	= 14; // Situação de preenchimento
	const TIPO_PRESTADOR			    	= 15; // Tipo de Prestador (Prefeitura ou Terceirizado)
    const RLC_INTERVENCOES_SISTEMA_DRENAGEM = 16; // Intervenções no sistema de drenagem
	const RLC_CLASSE_RIO         		    = 19; // Classe do rio
	const TIPO_GENERO				    	= 44; // Tipo de Prestador (Prefeitura ou Terceirizado)
	const PARTIDO_POLITICO			    	= 45; // Tipo de Prestador (Prefeitura ou Terceirizado)
	const PRONOME_TRATAMENTO		    	= 46; // Tipo de Prestador (Prefeitura ou Terceirizado)
	const SUCESSAO_CARGO_POLITICO	        = 47; // Tipo de Prestador (Prefeitura ou Terceirizado)
	const TIPO_AVISO_ERRO                   = 48; // Tipo de aviso e erro
    const TIPO_UNIDADE_INDICADOR            = 76; // Tipo de unidade do indicador
	
	/**
     * @inheritdoc
     */

    public function rules()
    {

        $rules = [
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

        return array_merge(parent::attributeLabels(), $labels);
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
        $query = TabAtributosSearch::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        // if (!$this->validate()) {
        // uncomment the following line if you do not want to return any records when validation fails
        // $query->where('0=1');
        // return $dataProvider;
        // }

        $query->andFilterWhere([
            $this->tableName() . '.cod_atributos' => $this->cod_atributos,
        ]);

        $query->andFilterWhere(['ilike', $this->tableName() . '.dsc_descricao', $this->dsc_descricao])
            ->andFilterWhere(['ilike', $this->tableName() . '.sgl_chave', $this->sgl_chave]);

        // $query->andWhere($this->tableName().'.dte_exclusao IS NULL');

        return $dataProvider;
    }

}
