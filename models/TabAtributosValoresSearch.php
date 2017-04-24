<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TabAtributosValores;
use yii\helpers\ArrayHelper;

/**
 * TabAtributosValoresSearch represents the model behind the search form about `app\models\TabAtributosValores`.
 */
class TabAtributosValoresSearch extends TabAtributosValores
{
	const NAO_INICIADO						= 55;
	const SENDO_REALIZADO_PELO_PRESTADOR	= 56;
	const FINALIZADO_PELO_PRESTADOR			= 58;
	const EM_ANALISE_ANALISTA				= 59;
	const EM_ANALISE_AUTOMATICA				= 60;
	const SENDO_REALIZADO_INTERNAMENTE		= 61;
	const FINALIZADO_INTERNAMENTE			= 62;
	const ANALISADO_E_FINALIZADO			= 63;
	const CANCELADO							= 64;
    
    // Scenarios
    const SCENARIO_ALTERAR_SITUACAO_PREENCHIMENTO = 'alterar_situacao_preenchimento';
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{

		$rules = [
            [['fk_atributos_valores_atributos_id'] , 'required' , 'on' => self::SCENARIO_ALTERAR_SITUACAO_PREENCHIMENTO , 'message' => '"Situação de preenchimento" não pode ficar em branco.'],
			[['fk_atributos_valores_atributos_id', 'sgl_valor', 'dsc_descricao'], 'safe'],
		];

		return $rules;

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
		$query			 = TabAtributosValoresSearch::find();
		$dataProvider	 = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);
		
		$query->andFilterWhere([
			$this->tableName() . '.cod_atributos_valores'				 => $this->cod_atributos_valores,
			$this->tableName() . '.fk_atributos_valores_atributos_id'	 => $this->fk_atributos_valores_atributos_id,
		]);

		$query->andFilterWhere(['ilike', $this->tableName() . '.sgl_valor', $this->sgl_valor])
			->andFilterWhere(['ilike', $this->tableName() . '.dsc_descricao', $this->dsc_descricao]);

		$query->orderBy('sgl_valor, dsc_descricao');

		return $dataProvider;

	}

	/**
	 * Metodo que busca os atributos valores de acordo com a fk passada por parâmetro
	 * @param int $fk_atributos_valores_atributos_id id do atributo conforme constante em TabAtributosSearch
	 * @param boolean $is_array
	 * @return Array
	 */
	public static function getAtributoValor($fk_atributos_valores_atributos_id, $is_array = true, $is_cod = false, $order = 'dsc_descricao')
	{
		if ($is_array) {
			$dados = self::find()
				->where('fk_atributos_valores_atributos_id=:fk_atributos_valores_atributos_id', [':fk_atributos_valores_atributos_id' => $fk_atributos_valores_atributos_id])
                ->orderBy($order)
				->asArray()
				->all();
			foreach ($dados as $key => $atributo_valor) {
				if ($is_cod) {
					$arr[] = [ 'value' => $atributo_valor['cod_atributos_valores'], 'text' => $atributo_valor['dsc_descricao']];
				} else {
					$arr[] = [ 'value' => $atributo_valor['sgl_valor'], 'text' => $atributo_valor['dsc_descricao']];
				}
			}
			$arr = ArrayHelper::map($arr, 'value', 'text');
		} else {
			/**
			 * @todo Ver a necessidade desta opção
			 */
			$arr = self::find()
				->where('fk_atributos_valores_atributos_id=:fk_atributos_valores_atributos_id', [':fk_atributos_valores_atributos_id' => $fk_atributos_valores_atributos_id])
				->asArray()
				->one();
		}

		return $arr;

	}

	/**
	 * Metodo que retorna a descrição de um atributo valor de acordo com a chave e valor
	 * @param int $fk_atributos_valores_atributos_id id do atributo conforme constante em TabAtributosSearch
	 * @param String $valor do índice do array do atributo valor
	 * @return String
	 */
	public static function getDescricaoAtributoValor($fk_atributos_valores_atributos_id, $valor)
	{
		$dados		 = self::find()
			->where('fk_atributos_valores_atributos_id=:fk_atributos_valores_atributos_id', [':fk_atributos_valores_atributos_id' => $fk_atributos_valores_atributos_id])
			->asArray()
			->all();
		$descricao	 = 'Não localizada';
		foreach ($dados as $key => $atributo_valor) {
			if ($atributo_valor['sgl_valor'] == $valor) {
				$descricao = $atributo_valor['dsc_descricao'];
				break;
			}
		}

		return $descricao;

	}
    
	/**
	 * Método que retorna as situações desabilitadas
	 * @return Array
	 */
	public static function getSituacoesDesabilitadas() {
		$arrSit = [
			TabAtributosValoresSearch::NAO_INICIADO,
			TabAtributosValoresSearch::SENDO_REALIZADO_PELO_PRESTADOR,
			TabAtributosValoresSearch::SENDO_REALIZADO_INTERNAMENTE,
			TabAtributosValoresSearch::CANCELADO,
		];
        
		return $arrSit;
	}
	public static function getIconeSitPre($cod)
	{
		$icone = '';
		switch ($cod) {
			case static::NAO_INICIADO:
				$icone = 'power-off'; // http://fontawesome.io/icon/power-off
				break;
			case static::SENDO_REALIZADO_INTERNAMENTE:
			case static::SENDO_REALIZADO_PELO_PRESTADOR:
				$icone = 'pencil-square-o'; // http://fontawesome.io/icon/pencil-square-o
				break;
			case static::FINALIZADO_PELO_PRESTADOR:
			case static::FINALIZADO_INTERNAMENTE:
				$icone = 'upload'; // http://fontawesome.io/icon/upload
				break;
			case static::EM_ANALISE_ANALISTA:
			case static::EM_ANALISE_AUTOMATICA:
				$icone = 'cogs'; // http://fontawesome.io/icon/cogs
				break;
			case static::ANALISADO_E_FINALIZADO:
				$icone = 'smile-o'; // http://fontawesome.io/icon/smile-o
				break;
			case static::CANCELADO:
				$icone = 'shield'; // http://fontawesome.io/icon/shield
				break;
			default:
				$icone = 'question-circle-o'; // http://fontawesome.io/icon/question-circle-o
				break;
		}
		
		return $icone;
	}
    
	/**
	 * metodo que retorna as situacoees habilitadas para usuario prestador
	 * @return Array
	 */
	public static function getSituacoesHabilitadasPrestador() {
		$arrSit = [
			TabAtributosValoresSearch::SENDO_REALIZADO_PELO_PRESTADOR,
            TabAtributosValoresSearch::SENDO_REALIZADO_INTERNAMENTE
		];
        
		return $arrSit;
	}
    
	/**
	 * metodo que retorna as situacoees habilitadas para usuario administrativo
	 * @return Array
	 */
	public static function getSituacoesHabilitadasAdministrativo() {
		$arrSit = [
            TabAtributosValoresSearch::SENDO_REALIZADO_PELO_PRESTADOR,
            TabAtributosValoresSearch::SENDO_REALIZADO_INTERNAMENTE,
            TabAtributosValoresSearch::FINALIZADO_PELO_PRESTADOR,
            TabAtributosValoresSearch::FINALIZADO_INTERNAMENTE,
            TabAtributosValoresSearch::EM_ANALISE_ANALISTA,
            TabAtributosValoresSearch::EM_ANALISE_AUTOMATICA,
			TabAtributosValoresSearch::CANCELADO
		];
        
		return $arrSit;
	}
    
}
