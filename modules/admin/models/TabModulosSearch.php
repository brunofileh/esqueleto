<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\TabModulos;
use app\models\RlcModulosPrestadores;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * TabModulosSearch represents the model behind the search form about `app\modules\admin\models\TabModulos`.
 */
class TabModulosSearch extends TabModulos
{

    public $lista_perfis;
    public $crop_info;
    public $txt_icone_crop;
	public $txt_icone_cropping;
	public $lista_temas = [	'skin-blue'=>'Blue', 
							'skin-black'=>'White', 
							'skin-purple'=>'Purple', 
							'skin-green'=>'Green',
							'skin-red'=>'Red',
							'skin-yellow'=>'Yellow',
		
							'skin-blue-light'=>'Blue Light',
							'skin-black-light'=>'White Light',
							'skin-purple-light'=>'Purple Light',
							'skin-green-light'=>'Green Light',
							'skin-red-light'=>'Red Light',
							'skin-yellow-light'=>'Yellow Light',
		];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['txt_nome', 'txt_equipe', 'txt_email_equipe', 'num_fones_equipe', 'flag_inicio_equipe'], 'required', 'on' => 'admin'],
            [['cod_modulo'], 'integer'],
			[['txt_icone_cropping'], 'file', 'extensions' => ['png', 'jpg', 'gif', 'ico'], 'maxSize' => 1024*1024],
            [['txt_nome', 'id', 'dsc_modulo', 'txt_url', 'txt_icone', 'txt_tema', 'txt_login_inclusao', 'dte_inclusao', 'dte_alteracao', 'lista_perfis', 'txt_icone_cropping', 'txt_icone_crop', 'txt_equipe', 'txt_email_equipe', 'num_fones_equipe', 'flag_inicio_equipe'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        $labelsParent = parent::attributeLabels();

        $label = [
            'cod_modulo' => 'Módulo',
            'txt_nome'   => 'Nome do Módulo',
			'txt_tema'   => 'Tema',
            'id'         => 'ID do Módulo',
			'txt_icone_cropping'         => 'Ícone',
            'txt_equipe' => 'Texto da equipe',
            'txt_email_equipe' => 'E-mail da equipe',
            'num_fones_equipe' => 'Telefones da equipe',
            'flag_inicio_equipe' => 'Mostrar equipe na página inicial do sistema?',
			];
        return array_merge($labelsParent, $label);
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
        $query = TabModulosSearch::find();

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
            $this->tableName() . '.cod_modulo'    => $this->cod_modulo,
            $this->tableName() . '.dte_inclusao'  => $this->dte_inclusao,
            $this->tableName() . '.dte_alteracao' => $this->dte_alteracao,
        ]);

        $query->andFilterWhere(['ilike', $this->tableName() . '.txt_nome', $this->txt_nome])
            ->andFilterWhere(['ilike', $this->tableName() . '.id', $this->id])
            ->andFilterWhere(['ilike', $this->tableName() . '.dsc_modulo', $this->dsc_modulo])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_url', $this->txt_url])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_icone', $this->txt_icone])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_login_inclusao', $this->txt_login_inclusao])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_equipe', $this->txt_equipe])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_email_equipe', $this->txt_email_equipe])
            ->andFilterWhere(['ilike', $this->tableName() . '.num_fones_equipe', $this->num_fones_equipe])
            ->andFilterWhere(['ilike', $this->tableName() . '.flag_inicio_equipe', $this->flag_inicio_equipe]);

        $query->andWhere($this->tableName() . '.dte_exclusao IS NULL');

        return $dataProvider;
    }

    public static function getInfo($id)
    {
        $getData = function () use ($id) {
            return static::findOneAsArray(['id' => $id]);
        };
        
        if (Yii::$app->params['habilitar-cache-global']) {
            $cacheKey = [Yii::$app->session->id, 'modulo', $id];
            if (($data     = Yii::$app->cache->get($cacheKey)) === false) {
                $data = $getData();
                Yii::$app->cache->set($cacheKey, $data);
            }
        } else {
            $data = $getData();
        }

        return $data;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRlcModulosPrestadores()
    {
        return $this->hasMany(RlcModulosPrestadores::className(), ['cod_modulo_fk' => 'cod_modulo']);
    }

    public function afterFind()
    {
        parent::afterFind();
		
		if ($this->txt_icone){
			if ( ! file_exists(Yii::getAlias('@webroot') . str_replace('@web', '' , $this->txt_icone )) ) {

			   $this->txt_icone = Url::home().'/img/logo-snis.png';

			} else {

				$this->txt_icone = $this->txt_icone;
			}
		}else{
			
			$this->txt_icone = $this->txt_icone;
		}
	
	}

    /**
     * Método para retorna a descrição no combo de pesquisa na grid
     * @param String $atributo Atributo que deseja recuperar os dados
     * @param string $valor Valor do atributo para pesquisa
     * @return String
     */
    public static function getTextoPorAtributo($atributo, $valor)
    {
        if ($atributo) {
            $object = (Object) self::find()
                ->where("$atributo=:atributo", [':atributo' => $valor])
                ->asArray()
                ->one();

            return $object->$atributo;
        } else
            return null;
    }

    /**
     * Retorna uma lista simples com o valor passado como atributo
     * @param String $atributo Atributo que deseja recuperar os dados
     * @return Array
     */
    public static function getListaPorAtributo($atributo)
    {
        $dados = self::find()
            ->select($atributo)
            ->andWhere(['is', self::tableName() . '.dte_exclusao', null])
            ->orderBy(self::tableName() . ".$atributo")
            ->asArray()
            ->all();
        foreach ($dados as $key => $atributo_valor) {
            $arr[] = [ 'value' => $atributo_valor[$atributo], 'text' => $atributo_valor[$atributo]];
        }
        $arr = ArrayHelper::map($arr, 'value', 'text');
        return $arr;
    }
    
    /**
     * retorna a equipe do modulo
     * @param String $id id do modulo
     * @return Array
     */
    public static function getEquipeModuloId($id)
    {
        $m = TabModulosSearch::find()
            ->where(['id' => $id])
            ->andWhere('txt_equipe IS NOT NULL')
            ->andWhere('txt_email_equipe IS NOT NULL')
            ->andWhere('num_fones_equipe IS NOT NULL')
            ->andWhere('dte_exclusao IS NULL')
            ->asArray()
            ->one();
        
        return $m;
    }
    
    /**
     * retorna as equipes dos modulos
     * @return Array
     */
    public static function getEquipesModulosInicio()
    {
        $m = TabModulosSearch::find()
            ->where(['flag_inicio_equipe' => 'S'])
            ->andWhere('txt_equipe IS NOT NULL')
            ->andWhere('txt_email_equipe IS NOT NULL')
            ->andWhere('num_fones_equipe IS NOT NULL')
            ->andWhere('dte_exclusao IS NULL')
            ->asArray()
            ->orderBy('txt_nome')
            ->all();
        
        return $m;
    }
}
