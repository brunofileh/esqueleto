<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TabMunicipios;
use yii\helpers\ArrayHelper;
use app\modules\admin\models\TabUsuariosPrestadoresSearch;

/**
 * TabMunicipiosSearch represents the model behind the search form about `app\models\TabMunicipios`.
 * 
 * @property TabEstadosSearch $tabEstados
 * @property TabPrestadoresSearch[] $tabPrestadores
 * @property RlcModulosPrestadoresSearch[] $rlcModulosPrestadores
 */
class TabMunicipiosSearch extends TabMunicipios
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
            'txt_nome' => 'Município', 
			'sgl_estado_fk' => 'UF',         
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
        $query = TabMunicipiosSearch::find();

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
            $this->tableName() . '.bln_indicador_capital' => $this->bln_indicador_capital,
            $this->tableName() . '.cod_microrregiao_fk' => $this->cod_microrregiao_fk,
            $this->tableName() . '.cod_ibge' => $this->cod_ibge,
            $this->tableName() . '.cod_regiao_metropolitana_fk' => $this->cod_regiao_metropolitana_fk,
        ]);

        $query->andFilterWhere(['ilike', $this->tableName() . '.cod_municipio', $this->cod_municipio])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_nome', $this->txt_nome])
            ->andFilterWhere(['ilike', $this->tableName() . '.sgl_estado_fk', $this->sgl_estado_fk]);

		$query->andWhere($this->tableName().'.dte_exclusao IS NULL');
		
        return $dataProvider;
    }
	
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getTabEstados()
    {
        return $this->hasOne(TabEstadosSearch::className(), ['sgl_estado' => 'sgl_estado_fk']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getTabPrestadores()
    {
        return $this->hasMany(TabPrestadoresSearch::className(), ['cod_municipio_fk' => 'cod_municipio']);
    }
	
		/**
     * @return \yii\db\ActiveQuery
     */
    public function getRlcModulosPrestadores()
    {
        return $this->hasMany(RlcModulosPrestadoresSearch::className(), ['cod_municipio_fk' => 'cod_municipio']);
    }
    
	/**
	 * metodo para descrever o municipio do prestador
	 *
	 * @param integer $cod_prestador
	 *
	 * @return String
	 */
	public static function getDescricaoMunicipioUsuarioPrestador($cod_prestador)
	{
        $dados = TabMunicipiosSearch::find()
			->select([TabMunicipiosSearch::tableName() . '.cod_municipio', TabMunicipiosSearch::tableName() . '.txt_nome', TabMunicipiosSearch::tableName() . '.sgl_estado_fk'])
			->innerJoin(TabPrestadoresSearch::tableName(), TabPrestadoresSearch::tableName() . '.cod_municipio_fk = ' . TabMunicipiosSearch::tableName() . '.cod_municipio')
            ->where(TabPrestadoresSearch::tableName() . '.cod_prestador = ' . $cod_prestador . '')
            ->asArray()
            ->one();
        $dsc_municipio = $dados['txt_nome'] . ' - ' . $dados['sgl_estado_fk'];
        
        return $dsc_municipio;
	}
    
	/**
	 * metodo para listar os municipios dos prestadores que possuem usuários cadastrados
	 *
	 * @param integer $cod_prestador
	 *
	 * @return Array
	 */
	public static function getMunicipiosUsuariosPrestadores($cod_prestador = null)
	{
		$dados = TabMunicipiosSearch::find()
			->select([TabMunicipiosSearch::tableName() . '.cod_municipio', TabMunicipiosSearch::tableName() . '.txt_nome || \' - \' || "sgl_estado_fk" AS txt_nome'])
			->innerJoin(TabPrestadoresSearch::tableName(), TabPrestadoresSearch::tableName() . '.cod_municipio_fk = ' . TabMunicipiosSearch::tableName() . '.cod_municipio')
			->innerJoin(TabUsuariosPrestadoresSearch::tableName(), TabPrestadoresSearch::tableName() . '.cod_prestador = ' . TabUsuariosPrestadoresSearch::tableName() . '.cod_prestador_fk')
            ->where(TabPrestadoresSearch::tableName() . '.dte_exclusao IS NULL')
            ->andWhere(TabUsuariosPrestadoresSearch::tableName() . '.dte_exclusao IS NULL')
			->orderBy(TabMunicipiosSearch::tableName() . '.txt_nome');
        
		if ($cod_prestador) {
			$dados->Where(['=', TabPrestadoresSearch::tableName() . '.cod_prestador', $cod_prestador]);
		}
        
		$arr = ArrayHelper::map($dados->all(), 'cod_municipio', 'txt_nome');
        
		return $arr;
	}
    
}
