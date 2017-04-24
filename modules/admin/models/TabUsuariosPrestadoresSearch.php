<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TabPrestadoresSearch;
use yii\helpers\ArrayHelper;
use yiibr\brvalidator\CpfValidator;

/**
 * TabUsuariosPrestadoresSearch represents the model behind the search form about `app\modules\admin\models\TabUsuarios`.
 */
class TabUsuariosPrestadoresSearch extends TabUsuariosSearch
{
	public $cod_perfil_fk;
    public $cod_municipio_fk;
    public $dsc_ativo;
	public $online =  false;
	public $lista_excessao_login;
	
	const SCENARIO_ADMIN = 'admin';
    const SCENARIO_USUARIO_PRESTADOR = 'usuario_prestador';
    const SCENARIO_REENVIAR_SENHA = 'reenviar_senha';
	
    /**
     * @inheritdoc
     */ 
    public function rules()
    {

		$rules =  [
            [['num_cpf'], CpfValidator::className()],
            [['txt_email'], 'email'],
			[['txt_login'], 'string', 'min' => 5, 'on' => self::SCENARIO_USUARIO_PRESTADOR] ,
			[['txt_login'] , 'match', 'pattern' => '/^[a-z0-9]+.[a-z0-9]+.[a-z0-9]+$/',   'message' => 'Caracter inválido. Permitidos [.] [a-z] [0-9]', 'on' => self::SCENARIO_USUARIO_PRESTADOR] ,
			[['cod_prestador_fk', 'txt_nome', 'txt_email', 'txt_senha', 'num_fone', 'txt_ativo', 'txt_tipo_login', 'txt_login' , 'num_cpf'], 'required', 'on' => self::SCENARIO_ADMIN],
            [['txt_nome', 'txt_email', 'txt_senha', 'txt_ativo', 'txt_tipo_login', 'txt_login', 'num_cpf', 'cod_prestador_fk', 'cod_perfil_fk', 'num_fone'], 'required', 'on' => self::SCENARIO_USUARIO_PRESTADOR],
            [['txt_senha'], 'required', 'on' => self::SCENARIO_REENVIAR_SENHA],
            [['cod_prestador_fk'], 'integer'],
            [['dte_inclusao', 'dte_alteracao', 'dte_exclusao', 'txt_login' , 'num_cpf', 'cod_perfil_fk', 'cod_municipio_fk', 'lista_excessao_login'], 'safe'],
            [['txt_nome'], 'string', 'max' => 70],
            [['txt_email', 'txt_login_inclusao'], 'string', 'max' => 150],
            [['txt_senha'], 'string', 'max' => 60],
            [['num_fone'], 'string', 'max' => 15],
            [['txt_ativo'], 'string', 'max' => 1],
			[ ['num_cpf'] , 'validaCPF' , 'on' => self::SCENARIO_ADMIN] ,
			[['num_cpf', 'txt_login', 'txt_email'], 'unique']
        ];
		
		return $rules;
    }
	
	/**
    * @inheritdoc
    */
	public function attributeLabels()
    {

		$labels = [
            'txt_email' => 'E-mail',
			'txt_ativo' => 'Ativo',
			'cod_prestador_fk' => 'Prestador',
            'cod_municipio_fk' => 'Município',
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
    public function search($params, $sort=null)
    {
        $query = TabUsuariosPrestadoresSearch::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->select(['*', TabSessaoPhp::tableName() . '.user_id as online', ' 
						case when (
								STRPOS(
									(SELECT $$|$$ || dsc_descricao || $$|$$ FROM public.tab_atributos_valores where fk_atributos_valores_atributos_id in 
										(SELECT cod_atributos
										FROM public.tab_atributos
										where sgl_chave=$$cod-usuario-problema-javascript$$)
									and sgl_valor=$$cod-usuario$$), 
									$$|$$||cod_usuario||$$|$$))>0 
							then true 
						else false end as lista_excessao_login'
						]);
		
        $query->andFilterWhere([
            $this->tableName() . '.cod_usuario' => $this->cod_usuario,
            $this->tableName() . '.qtd_acesso' => $this->qtd_acesso,
            $this->tableName() . '.dte_inclusao' => $this->dte_inclusao,
            $this->tableName() . '.dte_alteracao' => $this->dte_alteracao,
            $this->tableName() . '.dte_exclusao' => $this->dte_exclusao,
            $this->tableName() . '.cod_prestador_fk' => $this->cod_prestador_fk,
            $this->tableName() . '.txt_ativo' => $this->txt_ativo,
        ]);

        $query->andFilterWhere(['ilike', $this->tableName() . '.txt_nome', $this->txt_nome])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_login', $this->txt_login])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_email', $this->txt_email])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_senha', $this->txt_senha])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_trocar_senha', $this->txt_trocar_senha])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_tipo_login', $this->txt_tipo_login])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_login_inclusao', $this->txt_login_inclusao]);

		$query->andWhere($this->tableName().'.dte_exclusao IS NULL');
		$query->andWhere($this->tableName().'.cod_prestador_fk IS NOT NULL');
		
		if ($this->num_fone) {
			$query->andWhere("replace(replace(replace(replace(".$this->tableName().".num_fone, '(', ''), ')', '') ,'-', '') , ' ', '') LIKE '%".str_replace('(', '', str_replace(')', '', str_replace('-', '', str_replace(' ', '', str_replace('_', '', $this->num_fone)))))."%'");
		}
		
		$qs = isset(\Yii::$app->request->queryParams['TabUsuariosPrestadoresSearch'])
			? \Yii::$app->request->queryParams['TabUsuariosPrestadoresSearch']
			: [];
        
        if (isset($qs["cod_perfil_fk"]) && $qs["cod_perfil_fk"] != null) {
            $query->andWhere(['=', RlcUsuariosPerfisSearch::tableName() . '.cod_perfil_fk', $qs["cod_perfil_fk"]]);
            $query->innerJoin(RlcUsuariosPerfisSearch::tableName(), RlcUsuariosPerfisSearch::tableName() . '.cod_usuario_fk = ' . TabUsuariosPrestadoresSearch::tableName() . '.cod_usuario');
        }
        
        if (isset($qs["cod_municipio_fk"]) && $qs["cod_municipio_fk"] != null) {
            $query->innerJoin(TabPrestadoresSearch::tableName(), TabPrestadoresSearch::tableName() . '.cod_prestador = ' . TabUsuariosPrestadoresSearch::tableName() . '.cod_prestador_fk');
            $query->andWhere(['=', TabPrestadoresSearch::tableName() . '.cod_municipio_fk', $qs["cod_municipio_fk"]]);
        }
         
		$query->leftJoin(TabSessaoPhp::tableName(), 'cod_usuario = user_id and expire > :time', [':time' => time()]);
		
		if ($sort !== null) {
			$query->orderBy($sort);
		}
		elseif (!isset($qs["sort"])) {
			$query->orderBy(''.$this->tableName().'.txt_nome');		
		}
		
        return $dataProvider;
    }
	
    /**
     * método para tratar atributos nao informados na pagina
     * 
     * @return Model
     */	
    public function beforeValidate()
    {
		if ($this->isNewRecord) {
			$this->qtd_acesso = 0;
		}	
		$this->txt_trocar_senha = '0';
		$this->txt_tipo_login   = '2';
        
        if ($this->scenario == self::SCENARIO_REENVIAR_SENHA) {
            $this->txt_senha = Yii::$app->getSecurity()->generatePasswordHash($this->txt_senha);
        }
        
        return parent::beforeValidate();
    }
	
    /**
     * @todo Rever este metodo quando a classe atributo-valor existir
	 * 
     * Metodo para montar o combobox Sim|Nao
     * 
     * @return Array
     */
    public static function getSimNao($cod_prestador = null)
    {
		$dados = TabUsuariosPrestadoresSearch::find()
			->distinct(true)
			->select(['txt_ativo', 'CASE WHEN "txt_ativo" = \'N\' THEN \'[N] Não\' WHEN "txt_ativo" = \'S\' THEN \'[S] Sim\' END AS dsc_ativo'])
            ->where('cod_prestador_fk IS NOT NULL')
            ->andWhere('dte_exclusao IS NULL')
			->orderBy('dsc_ativo DESC');
		
		if ($cod_prestador) {
			$dados->andWhere(['=', 'cod_prestador_fk', $cod_prestador]);
		}
        
        $arr = ArrayHelper::map($dados->all(), 'txt_ativo', 'dsc_ativo');
        return $arr;
    }

    /**
     * @todo Rever este metodo quando a classe atributo-valor existir
	 * 
     * Metodo para descrever Sim|Nao
	 * 
	 * @param integer $attribute
     * 
     * @return String
     */
    public function getDescricaoSimNao($attribute)
    {
        return ($this->$attribute == 'S') ? 'Sim' : 'Não';
    }
    
	/**
	 * metodo para identificar usuarios com perfil "responsavel pela informacao" vinculados ao prestador
	 * @return array
	 */
	public static function verificarResponsavelPelaInformacao($model) {
        $usuarios = TabUsuariosPrestadoresSearch::find()
            ->innerJoin('acesso.rlc_usuarios_perfis as up', 'cod_usuario = up.cod_usuario_fk')
            ->innerJoin('acesso.tab_perfis as pe', 'up.cod_perfil_fk = pe.cod_perfil')
            ->where([
                'cod_prestador_fk'        => $model->cod_prestador_fk,
                'txt_ativo'               => 'S',
                'pe.txt_perfil_prestador' => '1'
            ])
            ->andwhere(TabUsuariosPrestadoresSearch::tableName() . '.cod_usuario <> ' . $model->cod_usuario . '')
            ->andwhere(TabUsuariosPrestadoresSearch::tableName() . '.dte_exclusao IS NULL')
            ->andwhere('up.dte_exclusao IS NULL')
            ->andwhere('pe.dte_exclusao IS NULL')
            ->asArray()
            ->all();
		
		return $usuarios;
	}    
	
}
