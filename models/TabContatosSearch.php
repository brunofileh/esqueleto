<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TabContatos;

/**
 * TabContatosSearch represents the model behind the search form about `app\models\TabContatos`.
 */
class TabContatosSearch extends TabContatos {

	public $radioCheck;
	public $txt_nome_outros;
	public $cod_modulo;
	public $prestador_fk;

	/**
	 * @inheritdoc
	 */
	public function rules() {

		$rules = [
			[['txt_nome', 'num_telefone', 'txt_email', 'dte_contato', 'txt_descricao', 'cod_form_fk', 'cod_categoria_fk', 'cod_sub_categoria_fk', 'cod_forma_contato_fk', 'cod_tipo_contato_fk'], 'required', 'on' => 'externo'],
			[['txt_nome_outros'], 'required', 'when' => function ($model) {
				return ($model->txt_nome == 'Outro');
			},
				'whenClient' => "function (attribute, value) {
				return $('#tabcontatossearch-txt_nome').val() == 'Outro';
			}"
			],
			[['txt_nome', 'num_telefone', 'txt_email', 'dte_contato', 'txt_descricao', 'cod_form_fk', 'cod_situacao_fk', 'cod_categoria_fk', 'cod_sub_categoria_fk', 'cod_forma_contato_fk', 'cod_tipo_contato_fk', 'cod_resposta_padrao_fk', 'cod_usuario_responsavel_fk', 'txt_resposta'], 'required', 'on' => 'interno'],
			[['cod_contato_relativo_fk', 'cod_modulo_fk', 'cod_form_fk', 'cod_categoria_fk', 'cod_sub_categoria_fk', 'cod_forma_contato_fk', 'cod_tipo_contato_fk', 'cod_situacao_fk', 'cod_usuario_responsavel_fk', 'cod_resposta_padrao_fk', 'cod_usuario_solicitante_fk'], 'integer'],
			[['dte_contato', 'dte_atendimento'], 'date'],
			[['txt_descricao', 'txt_resposta'], 'string'],
			['dte_atendimento', 'validateDataConsulta'],
			[['num_telefone'], 'string', 'max' => 15],
			[['txt_email'], 'email'],
			[['num_ano_ref', 'cod_prestador_fk', 'prestador_fk', 'txt_nome', 'txt_nome_outros', 'cod_contato'], 'safe'],
			[['txt_login_inclusao'], 'string', 'max' => 150]
		];

		return $rules;
	}

	public function validateDataConsulta($attribute, $params) {

		$formatData = function ($data) {
			$data = explode('/', $data);
			return $data[2] . $data[1] . $data[0];
		};

		if ($this->$attribute && ( $formatData($this->dte_contato) > $formatData($this->$attribute) )) {

			$this->addError($attribute, 'Data do contato não pode ser maior que a data de atendimento.');
		}
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		$labels = parent::attributeLabels();

		$labels = [
			'txt_nome_outros' => 'Informar o Nome',
				'cod_contato' => 'Código',
				'txt_nome' => 'Nome do contato',
		];

		return array_merge(parent::attributeLabels(), $labels);
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios() {
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
	public function search($params) {
		$query = TabContatosSearch::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		$date = explode('/', $this->dte_contato);

		if ($date[0]) {
			$date = $date[2] . '/' . $date[1] . '/' . $date[0];
		} else {
			$date = null;
		}
		
		$dateA = explode('/', $this->dte_atendimento);

		if ($dateA[0]) {
			$dateA = $dateA[2] . '/' . $dateA[1] . '/' . $dateA[0];
		} else {
			$dateA = null;
		}
		

		$query->andFilterWhere([
			$this->tableName() . '.cod_contato' => $this->cod_contato,
			$this->tableName() . '.cod_contato_relativo_fk' => $this->cod_contato_relativo_fk,
			$this->tableName() . '.cod_modulo_fk' => $this->cod_modulo_fk,
			$this->tableName() . '.dte_contato' => $date,
			$this->tableName() . '.dte_atendimento' => $dateA,
			$this->tableName() . '.cod_form_fk' => $this->cod_form_fk,
			$this->tableName() . '.cod_categoria_fk' => $this->cod_categoria_fk,
			$this->tableName() . '.cod_sub_categoria_fk' => $this->cod_sub_categoria_fk,
			$this->tableName() . '.cod_forma_contato_fk' => $this->cod_forma_contato_fk,
			$this->tableName() . '.cod_tipo_contato_fk' => $this->cod_tipo_contato_fk,
			$this->tableName() . '.cod_situacao_fk' => $this->cod_situacao_fk,
			$this->tableName() . '.cod_usuario_responsavel_fk' => $this->cod_usuario_responsavel_fk,
			$this->tableName() . '.cod_resposta_padrao_fk' => $this->cod_resposta_padrao_fk,
			$this->tableName() . '.cod_prestador_fk' => $this->cod_prestador_fk,
			$this->tableName() . '.num_ano_ref' => $this->num_ano_ref,
			$this->tableName() . '.cod_usuario_solicitante_fk' => $this->cod_usuario_solicitante_fk,
			$this->tableName() . '.dte_inclusao' => $this->dte_inclusao,
			$this->tableName() . '.dte_alteracao' => $this->dte_alteracao,
			$this->tableName() . '.dte_exclusao' => $this->dte_exclusao,
		]);


		/* 	if ($this->usuario_contato_fk) {
		  $query->innerJoin(TabUsuariosSearch::tableName(), self::tableName() . '.cod_usuario_contato_fk = ' . TabUsuariosSearch::tableName() . '.cod_usuario');
		  $query->andFilterWhere(['ilike', TabUsuariosSearch::tableName() . '.txt_nome', $this->usuario_contato_fk]);
		  } */


		$query->innerJoin(TabAtributosValoresSearch::tableName() . ' as s', self::tableName() . '.cod_situacao_fk = ' . 's.cod_atributos_valores');


		$query->innerJoin(VisConsultaPrestadoresSearch::tableName() . ' as p', self::tableName() . ".cod_prestador_fk=p.cod_prestador AND ano_ref='{$this->num_ano_ref}' and cod_modulo_fk={$this->cod_modulo}");

		if ($this->prestador_fk) {
			$query->andWhere("p.nome_prestador ilike '%{$this->prestador_fk}%'");
		}

		$query->andFilterWhere(['ilike', $this->tableName() . '.num_telefone', $this->num_telefone])
			->andFilterWhere(['ilike', $this->tableName() . '.txt_nome', $this->txt_nome])
			->andFilterWhere(['ilike', $this->tableName() . '.txt_email', $this->txt_email])
			->andFilterWhere(['ilike', $this->tableName() . '.txt_login_inclusao', $this->txt_login_inclusao]);
//			->andFilterWhere(['ilike', $this->tableName() . '.txt_nome' => $this->txt_nome]);

		$query->andWhere($this->tableName() . '.dte_exclusao IS NULL');

		$query->orderBy('s.sgl_valor, dte_contato desc');

		return $dataProvider;
	}

	public function afterFind() {
		parent::afterFind();

		$alterData = function($data) {

			$date = explode(' ', $data);
			$date = explode('-', $date[0]);
			$date = $date[2] . '/' . $date[1] . '/' . $date[0];

			return $date;
		};


		if ($this->dte_contato) {
			$this->dte_contato = $alterData($this->dte_contato);
		}

		if ($this->dte_atendimento) {
			$this->dte_atendimento = $alterData($this->dte_atendimento);
		}
	}

	public static function getListaContatos($cod_prestador, $cod_contato = null, $cod_contato_relativo = null, $situacao = null) {

		$query = new \app\models\TabContatosSearch();
		$dados = [];

		if (TabAtributosValoresSearch::findOneAsArray(["cod_atributos_valores" => ($situacao)])['sgl_valor'] == 1) {

			if ($cod_contato) {

				$dados = $query->find()->andWhere('dte_exclusao is null and cod_prestador_fk =' . $cod_prestador . ' and cod_contato <>' . $cod_contato)->orderBy('cod_contato_relativo_fk, dte_contato desc')->all();
			} else {
				$dados = $query->find()->andWhere('dte_exclusao is null and cod_prestador_fk =' . $cod_prestador)->orderBy('dte_contato desc')->orderBy('cod_contato_relativo_fk')->all();
			}
		} else {
			
			if($cod_contato_relativo)
			$dados = $query->find()->andWhere('cod_contato=' . $cod_contato_relativo)->orderBy('cod_contato_relativo_fk')->all();
			
		}
	
		if ($cod_contato_relativo) {
			foreach ($dados as $key => $dado) {
				if ($dado->cod_contato == $cod_contato_relativo) {
					$dado->radioCheck = true;
				
					unset($dados[$key]);
					
					array_unshift($dados, $dado);
				}
			}
		}

		$dataProvider = new \yii\data\ArrayDataProvider([
			'allModels' => $dados,
		]);

		return $dataProvider;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTabAtributosValores() {
		return $this->hasOne(TabAtributosValoresSearch::className(), ['cod_atributos_valores' => 'cod_form_fk']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTabAtributosValores0() {
		return $this->hasOne(TabAtributosValoresSearch::className(), ['cod_atributos_valores' => 'cod_categoria_fk']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTabAtributosValores1() {
		return $this->hasOne(TabAtributosValoresSearch::className(), ['cod_atributos_valores' => 'cod_sub_categoria_fk']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTabForm() {
		return $this->hasOne(TabForm::className(), ['cod_form' => 'cod_form_fk']);
	}

	public function getTabAtributosValores4() {
		return $this->hasOne(TabAtributosValores::className(), ['cod_atributos_valores' => 'cod_situacao_fk']);
	}

	public function getTabUsuarios() {
		return $this->hasOne(\app\modules\admin\models\TabUsuarios::className(), ['cod_usuario' => 'cod_usuario_responsavel_fk']);
	}

}
