<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\TabRestricoesUsuarios;
use app\modules\admin\models\RlcPerfisFuncionalidadesAcoes;
use app\modules\admin\models\TabPerfis;
use app\modules\admin\models\TabAcoes;
use app\modules\admin\models\TabFuncionalidades;
use yii\db\Query;

/**
 * TabRestricoesUsuariosSearch represents the model behind the search form about `app\modules\admin\models\TabRestricoesUsuarios`.
 */
class TabRestricoesUsuariosSearch extends TabRestricoesUsuarios
{

    const SCENARIO_RESTRICAO = 'restricao';

    public $lista_restricoes;
    public $modulo_perfil;
    public $podeVincular;

    /**
     * @inheritdoc
     */
    public function rules()
    {

        $rules = [
            [['lista_restricoes'], 'safe'],
//            [['modulo_perfil', 'lista_restricoes'], 'required', 'on' => self::SCENARIO_RESTRICAO],
            [['cod_usuario_fk', 'cod_perfil_funcionalidade_acao_fk'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [['modulo_perfil'], 'required', 'on' => self::SCENARIO_RESTRICAO],
            [['cod_usuario_fk', 'cod_perfil_funcionalidade_acao_fk'], 'integer'],
            [['dte_inclusao', 'dte_alteracao', 'dte_exclusao'], 'safe'],
            [['txt_login_inclusao'], 'string', 'max' => 150],
            [['cod_usuario_fk', 'cod_perfil_funcionalidade_acao_fk'], 'unique', 'targetAttribute' => ['cod_usuario_fk', 'cod_perfil_funcionalidade_acao_fk'], 'message' => 'The combination of Cod Usuario Fk and Cod Perfil Funcionalidade Acao Fk has already been taken.']
        ];

//        return array_merge($rules, parent::rules());
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {

        $labels = [
            'modulo_perfil'    => 'Módulo - Perfil',
            'lista_restricoes' => 'Funcionalidades/Ações restringidas',
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
        $query = TabRestricoesUsuariosSearch::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate())
        {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            $this->tableName() . '.cod_restricao_usuario'             => $this->cod_restricao_usuario,
            $this->tableName() . '.cod_usuario_fk'                    => $this->cod_usuario_fk,
            $this->tableName() . '.cod_perfil_funcionalidade_acao_fk' => $this->cod_perfil_funcionalidade_acao_fk,
            $this->tableName() . '.dte_inclusao'                      => $this->dte_inclusao,
            $this->tableName() . '.dte_alteracao'                     => $this->dte_alteracao,
            $this->tableName() . '.dte_exclusao'                      => $this->dte_exclusao,
        ]);

        $query->andFilterWhere(['ilike', $this->tableName() . '.txt_login_inclusao', $this->txt_login_inclusao]);

        $query->andWhere($this->tableName() . '.dte_exclusao IS NULL');

        return $dataProvider;
    }

    /**
     * Retorna a lista de 
     * @param String $tipo Tipo de lista: Todas=>null, Selecionados=>IN | Não Selecionados=>NOT IN
     */
    public function searchRestricao($tipo = null, $cod_usuario_fk, $cod_perfil_fk)
    {
        $subQuery = (new Query())
            ->select('rus.cod_perfil_funcionalidade_acao_fk')
            ->from(['rus' => TabRestricoesUsuarios::tableName()])
            ->where(['rus.cod_usuario_fk' => $cod_usuario_fk]);

        $command = (new Query())
            ->select([
                'pfa.cod_perfil_funcionalidade_acao',
                'p.cod_perfil',
                'f.dsc_funcionalidade',
                'a.dsc_acao',
                'f.dsc_funcionalidade'
            ])
            ->from(['pfa' => RlcPerfisFuncionalidadesAcoes::tableName()])
            ->innerJoin(['p' => TabPerfis::tableName()], 'pfa.cod_perfil_fk = p.cod_perfil')
            ->innerJoin(['a' => TabAcoes::tableName()], 'pfa.cod_acao_fk = a.cod_acao')
            ->innerJoin(['f' => TabFuncionalidades::tableName()], 'pfa.cod_funcionalidade_fk = f.cod_funcionalidade')
            ->where( ($tipo) ? [$tipo, 'pfa.cod_perfil_funcionalidade_acao', $subQuery] : '1=1')
            ->andWhere(['pfa.cod_perfil_fk' => $cod_perfil_fk])
            ->andWhere(['pfa.dte_exclusao' => null])
            ->orderBy('dsc_funcionalidade, dsc_acao')
            ->createCommand();
        return $command->queryAll();
    }
	
	/**
	 * Método para listar as Funcionalidades restritas por Usuário
	 *
	 * @param integer $cod_usuario
	 *
	 * @return Array
	 */
	public static function restricoesFuncionalidadesPorUsuario($cod_usuario)
	{
		$dados = TabRestricoesUsuariosSearch::find()
			->distinct(true)						
			->select(RlcPerfisFuncionalidadesAcoesSearch::tableName() . '.cod_funcionalidade_fk')						
			->innerJoin(RlcPerfisFuncionalidadesAcoesSearch::tableName(), TabRestricoesUsuariosSearch::tableName() . '.cod_perfil_funcionalidade_acao_fk = ' . RlcPerfisFuncionalidadesAcoesSearch::tableName() . '.cod_perfil_funcionalidade_acao')						
			->where(['=', TabRestricoesUsuariosSearch::tableName() . '.cod_usuario_fk', $cod_usuario])						
			->andWhere(TabRestricoesUsuariosSearch::tableName() . '.dte_exclusao IS NULL')
			->andWhere(RlcPerfisFuncionalidadesAcoesSearch::tableName() . '.dte_exclusao IS NULL')						
			->asArray()
			->all();										

		return $dados;
	}
	
}
