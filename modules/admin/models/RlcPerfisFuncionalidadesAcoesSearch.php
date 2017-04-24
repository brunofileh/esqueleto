<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\RlcPerfisFuncionalidadesAcoes;

/**
 * RlcPerfisFuncionalidadesAcoesSearch represents the model behind the search form about `app\modules\admin\models\RlcPerfisFuncionalidadesAcoes`.
 */
class RlcPerfisFuncionalidadesAcoesSearch extends RlcPerfisFuncionalidadesAcoes
{

	public $lista_acao;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{

		$rules = [
			[ ['lista_acao'], 'safe'],
		];

		return array_merge($rules, parent::rules());

	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{

		$labels = [
			 'cod_perfil_fk' => 'Perfil',
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
		$query = RlcPerfisFuncionalidadesAcoesSearch::find();

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
			$this->tableName() . '.cod_perfil_funcionalidade_acao'	 => $this->cod_perfil_funcionalidade_acao,
			$this->tableName() . '.cod_perfil_fk'					 => $this->cod_perfil_fk,
			$this->tableName() . '.cod_funcionalidade_fk'			 => $this->cod_funcionalidade_fk,
			$this->tableName() . '.cod_acao_fk'						 => $this->cod_acao_fk,
			$this->tableName() . '.dte_inclusao'					 => $this->dte_inclusao,
			$this->tableName() . '.dte_alteracao'					 => $this->dte_alteracao,
			$this->tableName() . '.dte_exclusao'					 => $this->dte_exclusao,
		]);

		$query->andFilterWhere(['ilike', $this->tableName() . '.txt_login_inclusao', $this->txt_login_inclusao]);

		$query->andWhere($this->tableName() . '.dte_exclusao IS NULL');

		return $dataProvider;

	}

}
