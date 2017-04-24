<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RlcModulosPrestadores;
use app\modules\admin\models\TabModulosSearch;
use app\modules\drenagem\models\TabParticipacoes as TabParticipacoesDrenagem;
use app\models\TabMunicipiosSearch;
use yii\helpers\ArrayHelper;

/**
 * RlcModulosPrestadoresSearch represents the model behind the search form about `app\models\RlcModulosPrestadores`.

 * @property TabModulosSearch $tabModulos
 * @property TabMunicipiosSearch $tabMunicipios
 * 
 */
class RlcModulosPrestadoresSearch extends RlcModulosPrestadores
{
    const SCENARIO_CONTATO_TECNICO = 'contato_tecnico';

	/**
	 * @inheritdoc
	 */
	public $listaMunicipios;
	public $sgl_estado_fk	 = [];
	public $qnt_prestadores	 = 0;
	public $qnt_regulador	 = 0;
	public $errosContatos	 = [
		'Responsável'	 => ['cp017', 'cp018', 'cp021', 'cp028', 'cp019'],
		'Encarregado'	 => ['cp032', 'cp033', 'cp036', 'cp042', 'cp034'],
		'Substituto'	 => ['cp046', 'cp047', 'cp050', 'cp056', 'cp048', 'cp049'],
	];

	public function rules()
	{
		$rules = [
			[ ['cp001', 'cp002', 'cp003', 'cp006', 'cp004', 'cp008', 'cp015',
					'cp017', 'cp018', 'cp021', 'cp028', 'cp019',
					'cp032', 'cp033', 'cp036', 'cp042', 'cp034',
					'cp046', 'cp047', 'cp050', 'cp056', 'cp048', 'cp049'
				], 'required', 'on' => 'gestorServico'],
			[ ['cp005'], 'safe', 'on' => 'gestorServico'],
			[['cp061','cp062'], 'required', 'on'=>'outrasEntidades'],
			[['cp061', 'cp062'], 'string', 'on'=>'outrasEntidades'],
            [['cp032', 'cp033', 'cp034', 'cp036', 'cp042'], 'required', 'on' => self::SCENARIO_CONTATO_TECNICO],
            [['cp042', 'cp043', 'cp056', 'cp057'], 'email'],
		];

		return ArrayHelper::merge(parent::rules(), $rules);

	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{

		$labels = [
			'cod_modulo_prestador' => 'Código do Modulo Prestador',
			'cod_prestador_fk'	   => 'Codigo do prestador',
			'cp001'				   => 'Nome da Secretaria/Departam/Setor responsavel pelo servico de Drenagem e Manejo de Águas Pluviais no municipio',
			'cp003'			       => 'Endereço',
			'cp006'				   => 'Bairro',
			'cp004'				   => 'Número',
			'cp002'				   => 'CEP',
			'cp007'				   => 'Site',
			'cp015'				   => 'E-mail',
			'cp008'				   => 'Telefone',
			'cp010'				   => 'Telefone 2',
			'cp012'				   => 'Fax',
			'cp032'				   => 'Nome',
			'cp033'				   => 'Cargo',
            'cp034'				   => 'Gênero',
            'cp037'				   => 'Ramal',
            'cp039'				   => 'Ramal 2',
            'cp040'				   => 'Fax',
            'cp041'				   => 'Ramal Fax',            
			'cp042'				   => 'E-mail',
            'cp043'				   => 'E-mail 2',
			'cp036'				   => 'Telefone',
			'cp038'				   => 'Telefone 2',
			'cp046'				   => 'Nome',
			'cp047'				   => 'Cargo',
            'cp048'				   => 'Gênero',
            'cp049'				   => 'Tratamento',
            'cp051'				   => 'Ramal',
            'cp053'				   => 'Ramal 2',
            'cp054'				   => 'Fax',
            'cp055'				   => 'Ramal Fax',            
			'cp056'				   => 'E-mail',
            'cp057'				   => 'E-mail 2',
			'cp050'				   => 'Telefone',
			'cp052'				   => 'Telefone2',
			'cod_municipio_fk'	   => 'Município',
			'txt_complemento'	   => 'Complemento',
			'cp061'	               => 'A Prefeitura é o único órgão responsável pelos serviços de Drenagem e Manejo de Águas Pluviais no município ?',
			'cp062'		           => 'Existe órgão ou entidade responsável pela regulação do serviços de Drenagem e Manejo de Águas Pluviais no município?',
			'bln_existe_servico'   => 'Existe Sistema de Drenagem no município?'
		];

		return array_merge(parent::attributeLabels(), $labels);

	}

	/**
	 * @inheritdoc
	 */
	public function scenarios()
	{
		$scenarios = parent::scenarios();

		//$scenarios['outrasEntidades'] = ['cp062', 'cp061'];
		return $scenarios;

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
		$query = RlcModulosPrestadoresSearch::find();

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
			$this->tableName() . '.cod_modulo_prestador' => $this->cod_modulo_prestador,
			$this->tableName() . '.cod_prestador_fk'	 => $this->cod_prestador_fk,
			$this->tableName() . '.cod_modulo_fk'		 => $this->cod_modulo_fk,
			$this->tableName() . '.dte_inclusao'		 => $this->dte_inclusao,
			$this->tableName() . '.dte_alteracao'		 => $this->dte_alteracao,
		]);

		$query->andFilterWhere(['ilike', $this->tableName() . '.cp001', $this->cp001])
			->andFilterWhere(['ilike', $this->tableName() . '.cp003', $this->cp003])
			->andFilterWhere(['ilike', $this->tableName() . '.cp006', $this->cp006])
			->andFilterWhere(['ilike', $this->tableName() . '.cp004', $this->cp004])
			->andFilterWhere(['ilike', $this->tableName() . '.cp002', $this->cp002])
			->andFilterWhere(['ilike', $this->tableName() . '.cp007', $this->cp007])
			->andFilterWhere(['ilike', $this->tableName() . '.cp015', $this->cp015])
			->andFilterWhere(['ilike', $this->tableName() . '.cp008', $this->cp008])
			->andFilterWhere(['ilike', $this->tableName() . '.cp010', $this->cp010])
			->andFilterWhere(['ilike', $this->tableName() . '.cp012', $this->cp012])
			->andFilterWhere(['ilike', $this->tableName() . '.cp032', $this->cp032])
			->andFilterWhere(['ilike', $this->tableName() . '.cp033', $this->cp033])
			->andFilterWhere(['ilike', $this->tableName() . '.cp042', $this->cp042])
			->andFilterWhere(['ilike', $this->tableName() . '.cp036', $this->cp036])
			->andFilterWhere(['ilike', $this->tableName() . '.cp038', $this->cp038])
			->andFilterWhere(['ilike', $this->tableName() . '.cp046', $this->cp046])
			->andFilterWhere(['ilike', $this->tableName() . '.cp047', $this->cp047])
			->andFilterWhere(['ilike', $this->tableName() . '.cp056', $this->cp056])
			->andFilterWhere(['ilike', $this->tableName() . '.cp050', $this->cp050])
			->andFilterWhere(['ilike', $this->tableName() . '.cp052', $this->cp052])
			->andFilterWhere(['ilike', $this->tableName() . '.txt_login_inclusao', $this->txt_login_inclusao]);

		$query->andWhere($this->tableName() . '.dte_exclusao IS NULL');

		return $dataProvider;

	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTabPrestadores()
	{
		return $this->hasOne(TabPrestadoresSearch::className(), ['cod_prestador' => 'cod_prestador_fk']);

	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTabModulos()
	{
		return $this->hasOne(TabModulosSearch::className(), ['cod_modulo' => 'cod_modulo_fk']);

	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTabMunicipios()
	{
		return $this->hasOne(TabMunicipiosSearch::className(), ['cod_municipio' => 'cod_municipio_fk']);

	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTabParticipacoes()
	{
		return $this->hasMany(TabParticipacoesDrenagem::className(), ['cod_modulo_prestador_fk' => 'cod_modulo_prestador']);

	}

	public function afterFind()
	{
		parent::afterFind();

		if ($this->cod_municipio_fk) {
			$this->sgl_estado_fk = TabMunicipiosSearch::findOneAsArray(['cod_municipio' => $this->cod_municipio_fk]);
		}
		
		if($this->cp061===false) $this->cp061 = 0;
		if($this->cp062===false) $this->cp062 = 0;

	}

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			$this->cp008	= str_replace('_', '', $this->cp008);
			$this->cp010	= str_replace('_', '', $this->cp010);
			$this->cp012	= str_replace('_', '', $this->cp012);
			$this->cp036	= str_replace('_', '', $this->cp036);
			$this->cp038	= str_replace('_', '', $this->cp038);
			$this->cp050	= str_replace('_', '', $this->cp050);
			$this->cp052	= str_replace('_', '', $this->cp052);
			$this->cp002	= str_replace('_', '', $this->cp002);
			
			
			$this->cp007 = strtolower($this->cp007);
			$this->cp015 = strtolower($this->cp015);
			$this->cp016 = strtolower($this->cp016);
			
			$this->cp028 = strtolower($this->cp028);
			$this->cp029 = strtolower($this->cp029);
			
			$this->cp042 = strtolower($this->cp042);
			$this->cp043 = strtolower($this->cp043);
			
			$this->cp056 = strtolower($this->cp056);
			$this->cp057 = strtolower($this->cp057);			
			return true;
		}

		return false;
	}

	public function setListaMunicipios()
	{
		if ($this->sgl_estado_fk) {
			$this->listaMunicipios = ArrayHelper::map(
				TabMunicipiosSearch::find()->where(['sgl_estado_fk' => $this->sgl_estado_fk])->orderBy('txt_nome')->asArray()->all(), 
				'cod_municipio', 
				'txt_nome'
			);
		} else {
			$this->listaMunicipios = [];
		}
	}

	/**
	 * Método restornar quais abas estão com erro
	 * verificaErroAbas
	 * @return String
	 */
	public function verificaErroAbas($tipo = 'errosContatos')
	{
		$erros = [];

		foreach ($this->errors as $keyErro => $valorErro) {
			foreach ($this->{$tipo} as $kAba => $valorAba) {

				if (in_array($keyErro, $valorAba)) {
					if (!in_array($kAba, $erros))
						$erros[] = $kAba;
				}
			}
		}

		return implode(', ', $erros);
	}
}
