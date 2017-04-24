<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\RlcUsuariosPerfis;

/**
 * RlcUsuariosPerfisSearch represents the model behind the search form about `app\modules\admin\models\RlcUsuariosPerfis`.
 */
class RlcUsuariosPerfisSearch extends RlcUsuariosPerfis
{
	/**
	 * @inheritdoc
	 */
	public $lista_usuarios;

	public function rules()
	{
		$rules = [
			[ ['lista_usuarios'], 'safe'],
		];

		return array_merge($rules, parent::rules());
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		$labels = [];
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
		$query = RlcUsuariosPerfis::find();

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
			$this->tableName() . '.cod_usuario_perfil'	 => $this->cod_usuario_perfil,
			$this->tableName() . '.cod_usuario_fk'		 => $this->cod_usuario_fk,
			$this->tableName() . '.cod_perfil_fk'		 => $this->cod_perfil_fk,
			$this->tableName() . '.dte_inclusao'		 => $this->dte_inclusao,
			$this->tableName() . '.dte_alteracao'		 => $this->dte_alteracao,
			$this->tableName() . '.dte_exclusao'		 => $this->dte_exclusao,
		]);

		$query->andFilterWhere(['ilike', 'txt_login_inclusao', $this->txt_login_inclusao]);
		$query->andWhere($this->tableName() . '.dte_exclusao IS NULL');
		return $dataProvider;
	}

	/**
	 * Método para listar os Perfis e Módulos por Usuário
	 *
	 * @param integer $cod_usuario
	 *
	 * @return Array
	 */
	public static function perfisModulosPorUsuario($cod_usuario)
	{
		$dados = RlcUsuariosPerfisSearch::find()
			->select(TabPerfisSearch::tableName() . '.cod_perfil,' . TabPerfisSearch::tableName() . '.txt_nome,' . TabPerfisSearch::tableName() . '.cod_modulo_fk')
			->innerJoin(TabPerfisSearch::tableName(), TabPerfisSearch::tableName() . '.cod_perfil = ' . RlcUsuariosPerfisSearch::tableName() . '.cod_perfil_fk')
			->where(['=', RlcUsuariosPerfisSearch::tableName() . '.cod_usuario_fk', $cod_usuario])
			->andWhere(TabPerfisSearch::tableName() . '.dte_exclusao IS NULL')
			->andWhere(RlcUsuariosPerfisSearch::tableName() . '.dte_exclusao IS NULL')
			->asArray()
			->all();

		return $dados;
	}

	/**
	 * Metodo para atualizacao do contador de acesso do usuario
	 * @return boolean
	 */
	public static function atualizarQtdAcesso($userPerfil, $sessao = null)
	{
		$cache = Yii::$app->cache->get($sessao);
		if ($cache) {
			if ($sessao[0] == $cache['sessao_db']) {
				return $cache;
			}
		}

		$model = self::find()
			->where([
				'cod_usuario_fk' => $userPerfil['cod_usuario_fk'],
				'cod_perfil_fk' => $userPerfil['cod_perfil_fk'],
			])
			->one();

		if (!isset($model->txt_sessao) && $sessao[0] != $model->txt_sessao) {
			
			$model->qtd_acesso++;
			$model->txt_sessao = $sessao[0];

			if (!$model->save()) {
				return false;
			}
		}
		else {
			return ['qtd_acesso_perfil' => $model->qtd_acesso, 'sessao_db' => $model->txt_sessao];
		}

		return ['qtd_acesso_perfil' => $model->qtd_acesso, 'sessao_db' => $model->txt_sessao];
	}
    
}