<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\VisUsuariosPerfis;

/**
 * This is the model class for table "acesso.vis_usuarios_perfis".
 *
 */
class VisUsuariosPerfisSearch extends VisUsuariosPerfis {

	public $cod_modulo;

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['qtd_acesso', 'cod_prestador_fk', 'cod_usuario_fk', 'cod_perfil_fk', 'cod_modulo_fk'], 'integer'],
			[['dte_sessao'], 'safe'],
			[['nome_usuario'], 'string', 'max' => 70],
			[['txt_email', 'dsc_perfil', 'dsc_modulo'], 'string', 'max' => 150],
			[['txt_senha'], 'string', 'max' => 60],
			[['num_fone', 'num_ip'], 'string', 'max' => 15],
			[['txt_trocar_senha', 'txt_ativo', 'txt_tipo_login', 'txt_perfil_prestador'], 'string', 'max' => 1],
			[['txt_login', 'modulo_icone'], 'string', 'max' => 100],
			[['num_cpf'], 'string', 'max' => 14],
			[['nome_perfil', 'nome_modulo'], 'string', 'max' => 80],
			[['modulo_id'], 'string', 'max' => 40],
			[['modulo_url'], 'string', 'max' => 200]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{

		$labels = [
			'cod_usuario_fk'		 => 'Código do Usuário' ,
			'txt_login'			 => 'Login' ,
			'nome_usuario'			 => 'Usuário' ,
			'txt_ativo'				 => 'Ativo' ,
			'uf'					 => 'Estado' ,
			'municipio'				 => 'Município' ,
			'txt_senha_confirma'	 => 'Confirmar senha' ,
			'cod_prestador_fk'		 => 'Prestador' ,
			'cod_modulo_fk'			 => 'Módulos',
			'num_cpf'				 => 'CPF',
			'txt_email_confirma'	 => 'Confirmar e-mail' ,
			'txt_senha_atual'		 => 'Atual senha' ,
			'txt_imagem_cropping'	 => 'Imagem',
            'txt_email'	             => 'E-mail',
            'cod_perfil_fk'	         => 'Perfil'
		];

		return array_merge( parent::attributeLabels() , $labels );

	}
	
	public function scenarios() {
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	public static function getModulosPerfisUsuario($codUsuario) {
		$getData = function () use ($codUsuario) {
			return static::findAllAsArray(['cod_usuario_fk' => $codUsuario]);
		};

		if (Yii::$app->params['habilitar-cache-global']) {
			$cacheKey = [Yii::$app->session->id, 'usuario', $codUsuario];
			if (($data = Yii::$app->cache->get($cacheKey)) === false) {
				$data = $getData();
				Yii::$app->cache->set($cacheKey, $data);
			}
		} else {
			$data = $getData();
		}

		return $data;
	}

	public static function getUsuarioPerfilModulos($moduloID, $codUsuario) {
		$getData = function () use ($moduloID, $codUsuario) {
			return VisUsuariosPerfisSearch::findOneAsArray([
					'UPPER(modulo_id)' => strtoupper($moduloID),
					'cod_usuario_fk' => $codUsuario,
			]);
		};

		if (Yii::$app->params['habilitar-cache-global']) {
			$cacheKey = [Yii::$app->session->id, 'usuario', $codUsuario, 'modulo', $moduloID];
			if (($data = Yii::$app->cache->get($cacheKey)) === false) {
				$data = $getData();
				Yii::$app->cache->set($cacheKey, $data);
			}
		} else {
			$data = $getData();
		}

		return $data;
	}

	public static function getVinculoUsuariosPerfil($cod_modulo_fk, $cod_perfil) {
		return self::find()->select(['cod_usuario_fk', 'txt_login'])->where("
									( 
										(
										 cod_modulo_fk<> {$cod_modulo_fk} 
										)  
									OR cod_perfil_fk IS NULL 
									) 
									 and cod_usuario_fk not in (
									 
										SELECT cod_usuario_fk 
										FROM acesso.vis_usuarios_perfis 
										WHERE ( (cod_modulo_fk={$cod_modulo_fk} ) ) )
											
										")->union(
				"SELECT cod_usuario_fk, txt_login 
											FROM acesso.vis_usuarios_perfis 
											WHERE cod_modulo_fk= {$cod_modulo_fk}
											and cod_perfil_fk= {$cod_perfil}"
		);
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params) {
		$query = VisUsuariosPerfisSearch::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			]);

		$this->load($params);

//        if (!$this->validate())
//        {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

		$query->andFilterWhere([
			$this->tableName() . '.cod_usuario_fk' => $this->cod_usuario_fk,
			$this->tableName() . '.qtd_acesso' => $this->qtd_acesso,
			$this->tableName() . '.cod_perfil_fk' => $this->cod_perfil_fk,
				$this->tableName() . '.cod_modulo_fk' => $this->cod_modulo_fk,
		]);

		$query->andFilterWhere(['ilike', $this->tableName() . '.nome_usuario', $this->nome_usuario])
			->andFilterWhere(['ilike', $this->tableName() . '.txt_email', $this->txt_email])
			->andFilterWhere(['ilike', $this->tableName() . '.txt_login', $this->txt_login])
			->andFilterWhere(['ilike', $this->tableName() . '.num_cpf', $this->num_cpf])	
			->andFilterWhere(['ilike', $this->tableName() . '.txt_senha', $this->txt_senha])
			->andFilterWhere(['ilike', $this->tableName() . '.num_fone', $this->num_fone])
			//->andFilterWhere(['ilike', $this->tableName() . '.cod_perfil_fk', $this->cod_perfil_fk])
			->andFilterWhere(['ilike', $this->tableName() . '.txt_trocar_senha', $this->txt_trocar_senha])
			->andFilterWhere(['ilike', $this->tableName() . '.txt_ativo', $this->txt_ativo])
			->andFilterWhere(['ilike', $this->tableName() . '.txt_tipo_login', $this->txt_tipo_login]);

		$query->orderBy('nome_usuario');
		$query->andWhere($this->tableName() . '.cod_prestador_fk IS NULL');

		return $dataProvider;
	}

}
