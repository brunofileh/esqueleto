<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\VisMenusPerfis;


class VisMenusPerfisSearch extends VisMenusPerfis
{

	/**
	 * @inheritdoc
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();

	}

	public function attributeLabels()
	{


		$labels = [
			'nome_menu'		 => 'Nome',
			'dsc_menu'		 => 'Descrição',
			'txt_url'		 => 'URL',
			'txt_imagem'	 => 'Imagem',
			'num_ordem'		 => 'Órdem',
			'num_nivel'		 => 'Nível',
			'nome_perfil'	 => 'Nome do Perfil',
			'dsc_perfil'	 => 'Descrição Perfil',
			'nome_menu_pai'	 => 'Menu Pai',
			'dsc_menu_pai'	 => 'Descricao Menu Pai',
		];

		return array_merge( parent::attributeLabels(), $labels );

	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search( $params )
	{
		$query = VisMenusPerfisSearch::find();

		$dataProvider = new ActiveDataProvider( [
			'query' => $query,
			] );

		$this->load( $params );


		$query->andFilterWhere( [
			$this->tableName() . '.cod_perfil_fk'						 => $this->cod_perfil_fk,
			$this->tableName() . '.cod_perfil_funcionalidade_acao_fk'	 => $this->cod_perfil_funcionalidade_acao_fk,
			$this->tableName() . '.cod_menu_fk'						 => $this->cod_menu_fk,
			$this->tableName() . '.cod_menu_pai_fk'					 => $this->cod_menu_pai_fk,
			$this->tableName() . '.cod_modulo_fk'						 => $this->cod_modulo_fk,
			$this->tableName() . '.cod_usuario_fk'					 => $this->cod_usuario_fk,
			$this->tableName() . '.num_ordem'							 => $this->num_ordem,
			$this->tableName() . '.num_nivel'							 => $this->num_nivel,
		] );


		$query->andFilterWhere( ['ilike', $this->tableName() . '.nome_menu', $this->nome_menu] )
			->andFilterWhere( ['ilike', $this->tableName() . '.dsc_menu', $this->dsc_menu] )
			->andFilterWhere( ['ilike', $this->tableName() . '.nome_menu_pai', $this->nome_menu_pai] )
			->andFilterWhere( ['ilike', $this->tableName() . '.dsc_menu_pai', $this->dsc_menu_pai] )
			->andFilterWhere( ['ilike', $this->tableName() . '.txt_url', $this->txt_url] )
			->andFilterWhere( ['ilike', $this->tableName() . '.txt_imagem', $this->txt_imagem] )
			->andFilterWhere( ['ilike', $this->tableName() . '.nome_perfil', $this->nome_perfil] )
			->andFilterWhere( ['ilike', $this->tableName() . '.dsc_perfil', $this->dsc_perfil] );

		return $dataProvider;

	}

}
