<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\MdlUsuarios;
use app\modules\admin\models\TabUsuarios;
use yii\helpers\Security;
use yii\helpers\ArrayHelper;
use \yii\helpers\Url;
use yiibr\brvalidator\CpfValidator;

/**
 * TabUsuariosSearch represents the model behind the search form about `app\modules\admin\models\MdlUsuarios`.
 */
class TabUsuariosSearch extends TabUsuarios
{
	const SCENARIO_LOGIN					   = 'login';
	const SCENARIO_ADMIN					   = 'admin';
	const SCENARIO_REGISTER					   = 'register';
	const SCENARIO_RECUPERAR_SENHA_SOLICITACAO = 'recuperar_senha_solicitacao';
	const SCENARIO_RECUPERAR_SENHA_CONFIRMACAO = 'recuperar_senha_confirmacao';
	const SCENARIO_ALTERAR_SENHA			   = 'alterar_senha';
    const SCENARIO_USUARIO_PRESTADOR		   = 'usuario_prestador';

	public $uf;
	public $municipio;
	public $txt_senha_confirma;
	public $modulos;
	public $txt_email_confirma;
	public $txt_senha_atual;
	public $txt_imagem_crop;
	public $txt_imagem_cropping;
	public $lista_modulos;
	public $sessao_ativa = true;
	public $reCaptcha;
    public $cod_perfil_fk;
	public $cod_perfil;
	public $cod_modulo;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$rules = [
            [ ['num_cpf'], CpfValidator::className()] ,
			[ ['txt_nome' , 'txt_email' , 'txt_senha' , 'txt_ativo' , 'txt_tipo_login' , 'txt_login' , 'num_cpf'] , 'required' , 'on' => self::SCENARIO_ADMIN] ,
			[ ['txt_login'], 'string', 'min' => 5, 'on' => [ self::SCENARIO_ADMIN , self::SCENARIO_REGISTER]] ,
			[ ['txt_login'] , 'match', 'pattern' => '/^[a-z0-9]+.[a-z0-9]+.[a-z0-9]+$/',   'message' => 'Caracter inválido. Permitidos [.] [a-z] [0-9]', 'on' => [ self::SCENARIO_ADMIN , self::SCENARIO_REGISTER]] ,
			[ ['uf' , 'cod_prestador_fk' , 'txt_email' , 'txt_senha' , 'municipio' , 'txt_senha_confirma' , 'num_fone' , 'txt_nome', 'modulos', 'num_cpf' , 'txt_login' , 'cod_perfil_fk'] , 'required' , 'on' => self::SCENARIO_REGISTER] ,
			[ ['txt_email' , 'txt_email_confirma'] , 'required' , 'on' => self::SCENARIO_RECUPERAR_SENHA_SOLICITACAO] ,
			[ [/* 'txt_senha_atual' , */ 'txt_senha' , 'txt_senha_confirma'] , 'required' , 'on' => self::SCENARIO_ALTERAR_SENHA] ,
			[ ['qtd_acesso'] , 'integer'] ,
			[ ['txt_imagem_cropping'] , 'file' , 'extensions' => ['png' , 'jpg' , 'gif'] , 'maxSize' => 1024 * 1024] ,
			[ ['dte_inclusao' , 'dte_alteracao' , 'dte_exclusao' , 'txt_imagem_crop' , 'txt_imagem' , 'txt_imagem_cropping', 'txt_login' , 'num_cpf'] , 'safe'] ,
			[ ['txt_nome'] , 'string' , 'max' => 70] ,
			[ ['txt_email' , 'txt_login_inclusao'] , 'string' , 'max' => 150] ,
			[ ['txt_senha'] , 'string' , 'max' => 60, 'min' => 5] ,
			[ ['num_fone'] , 'string' , 'max' => 15] ,
			[ ['txt_trocar_senha' , 'txt_ativo' , 'txt_tipo_login'] , 'string' , 'max' => 1] ,
			[ ['txt_email'] , 'email'] ,
			[ ['num_cpf' , 'txt_login' ] , 'unique' , 'on' => [ self::SCENARIO_ADMIN , self::SCENARIO_REGISTER]] ,
			[ ['txt_senha'] , 'validarEmailSenha' , 'on' => self::SCENARIO_LOGIN] ,
			[ ['txt_email_confirma'] , 'validarEmailRecuperarSenha' , 'on' => self::SCENARIO_RECUPERAR_SENHA_SOLICITACAO] ,
			[ ['txt_senha_confirma'] , 'validarSenhaRecuperarSenha' , 'on' => self::SCENARIO_RECUPERAR_SENHA_CONFIRMACAO] ,
			[ ['num_cpf'] , 'validaCPF' , 'on' => self::SCENARIO_ADMIN] ,
			[ ['txt_senha_atual'] , 'confirmarSenhaAtual' , 'on' => self::SCENARIO_ALTERAR_SENHA] , 
			[ ['txt_login' , 'txt_senha'] , 'required' , 'on' => self::SCENARIO_LOGIN] ,
			[ ['txt_senha_confirma'] , 'compare' , 'compareAttribute' => 'txt_senha' , 'on' => [self::SCENARIO_REGISTER , self::SCENARIO_ALTERAR_SENHA]] ,
		];

//		if (YII_ENV == 'prod') {
//			$rules[] = [ ['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => '6LeB2R8TAAAAACoGq6XXmxOjo-PC1Nu6hj71yXJX', 'on' => self::SCENARIO_LOGIN];
//		}

		return $rules;
	}

	/**
	 * @todo Ver para que serve/servirá esse método
	 * @param type $attribute
	 * @param type $params
	 * @return boolean
	 */
	public function validarEmailSenha( $attribute , $params )
	{
		return true;

	}

	/**
	 * Verifica se o usuario informou emails iguais para recuperação da senha    
	 * @return boolean
	 */
	public function validarEmailRecuperarSenha()
	{
		if ($this->txt_email != $this->txt_email_confirma){
			$this->addError( 'txt_email_confirma' , 'Os campos ' . self::getAttributeLabel( 'txt_email' ) . ' e ' . self::getAttributeLabel( 'txt_email_confirma' ) . ' devem ser iguais.' );
		}
		return true;

	}

	/**
	 * Verifica se o usuario informou as senhas iguais para recuperação da senha    
	 * @return boolean
	 */
	public function validarSenhaRecuperarSenha()
	{
		if ($this->txt_senha != $this->txt_senha_confirma){
			$this->addError( 'txt_senha_confirma' , 'Os campos ' . self::getAttributeLabel( 'txt_senha' ) . ' e ' . self::getAttributeLabel( 'txt_senha_confirma' ) . ' devem ser iguais.' );
		}
		return true;

	}

	/**
	 * Verifica se o cpf é valido
	 * @return boolean
	 */
	public function validaCPF( $attribute , $params )
	{

		$cpf = str_pad( preg_replace( '/[^0-9]/' , '' , $this->num_cpf ) , 11 , '0' , STR_PAD_LEFT );

		// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
		if (strlen( $cpf ) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999'){

			$this->addError( 'num_cpf' , 'CPF Inválido' );
		} else{ // Calcula os números para verificar se o CPF é verdadeiro
			for ($t = 9; $t < 11; $t++){
				for ($d = 0 , $c = 0; $c < $t; $c++){
					$d += $cpf{$c} * (($t + 1) - $c);
				}
				$d = ((10 * $d) % 11) % 10;
				if ($cpf{$c} != $d){
					$this->addError( 'num_cpf' , 'CPF Inválido' );
				}
			}
			return TRUE;
		}

	}

	/**
	 * Verifica se o usuário informou a senha atual correta
	 * @return boolean
	 */
	public function confirmarSenhaAtual()
	{
		$user = TabUsuariosSearch::find()->Where( 'cod_usuario = ' . $this->cod_usuario )->one();

		if ($user && !\Yii::$app->getSecurity()->validatePassword( $this->txt_senha_atual , $user->txt_senha )){
			$this->addError( 'txt_senha_atual' , 'A ' . self::getAttributeLabel( 'txt_senha_atual' ) . ' não confere com a ' . self::getAttributeLabel( 'txt_senha' ) . ' cadastrada.' );
		}

		return true;

	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{

		$labels = [
			'cod_usuario'			 => 'Código do Usuário' ,
			'txt_ativo'				 => 'Ativo' ,
			'uf'					 => 'Estado' ,
			'municipio'				 => 'Município' ,
			'txt_senha_confirma'	 => 'Confirmar senha' ,
			'cod_prestador_fk'		 => 'Prestador' ,
			'modulos'				 => 'Módulos' ,
			'txt_email_confirma'	 => 'Confirmar e-mail' ,
			'txt_senha_atual'		 => 'Atual senha' ,
			'txt_imagem_cropping'	 => 'Imagem',
            'txt_email'	             => 'E-mail',
            'cod_perfil_fk'	         => 'Perfil',
			'cod_perfil'	         => 'Perfil',
			'cod_modulo'	         => 'Modulo'
		];

		return array_merge( parent::attributeLabels() , $labels );

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
	public function search( $params )
	{
		$query = MdlUsuarios::find();

		$dataProvider = new ActiveDataProvider( [
			'query' => $query ,
			] );

		$this->load( $params );

//        if (!$this->validate())
//        {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

		$query->andFilterWhere( [
			$this->tableName() . '.cod_usuario'		 => $this->cod_usuario ,
			$this->tableName() . '.qtd_acesso'		 => $this->qtd_acesso ,
			$this->tableName() . '.dte_inclusao'	 => $this->dte_inclusao ,
			$this->tableName() . '.dte_alteracao'	 => $this->dte_alteracao ,
			$this->tableName() . '.dte_exclusao'	 => $this->dte_exclusao ,
		] );

		$query->andFilterWhere( ['ilike' , $this->tableName() . '.txt_nome' , $this->txt_nome] )
			->andFilterWhere( ['ilike' , $this->tableName() . '.txt_email' , $this->txt_email] )
			->andFilterWhere( ['ilike' , $this->tableName() . '.txt_senha' , $this->txt_senha] )
			->andFilterWhere( ['ilike' , $this->tableName() . '.num_fone' , $this->num_fone] )
			->andFilterWhere( ['ilike' , $this->tableName() . '.txt_trocar_senha' , $this->txt_trocar_senha] )
			->andFilterWhere( ['ilike' , $this->tableName() . '.txt_ativo' , $this->txt_ativo] )
			->andFilterWhere( ['ilike' , $this->tableName() . '.txt_tipo_login' , $this->txt_tipo_login] )
			->andFilterWhere( ['ilike' , $this->tableName() . '.txt_login_inclusao' , $this->txt_login_inclusao] );

        $query->andWhere($this->tableName() . '.dte_exclusao IS NULL');
		$query->orderBy('txt_nome');
		$query->andWhere( $this->tableName() . '.cod_prestador_fk IS NULL' );

		return $dataProvider;

	}

	public function beforeValidate()
	{
		$this->num_fone = str_replace( "_" , "" , $this->num_fone );
		if ($this->isNewRecord){
			/**
			 * @todo automatico ou informado pelo usuario na inclusao
			 */
			$this->txt_ativo		 = 'S';
			$this->txt_tipo_login	 = ($this->cod_prestador_fk) ? '2' : '1';
			$this->txt_trocar_senha	 = '0';
			$this->qtd_acesso		 = 0;
			$identity				 = Yii::$app->user->identity;
			if ($identity){
				$this->txt_login_inclusao = $identity->txt_login_inclusao;
			}
		} else{
			if ($this->txt_ativo == 'S'){
				$this->dte_exclusao = null;
			}
		}
		return parent::beforeValidate();

	}

	public function beforeSave( $insert )
	{
		if ($this->scenario == self::SCENARIO_ADMIN){
			$senha_antiga = ($this->oldAttributes) ? $this->oldAttributes['txt_senha'] : '';
			// verifica se a senha atual é diferente da senha digitada, se for gera o hash e salva no banco
			if ($this->attributes['txt_senha'] != $senha_antiga){
				$this->txt_senha = Yii::$app->getSecurity()->generatePasswordHash( $this->txt_senha );
			}
		} elseif ($this->scenario == self::SCENARIO_REGISTER || $this->scenario == self::SCENARIO_USUARIO_PRESTADOR){
            if ($this->isNewRecord)
                $this->txt_senha = Yii::$app->getSecurity()->generatePasswordHash( $this->txt_senha );
		} elseif ($this->scenario == self::SCENARIO_RECUPERAR_SENHA_CONFIRMACAO || $this->scenario == self::SCENARIO_ALTERAR_SENHA){
			$this->txt_trocar_senha	 = '0';
			$this->txt_senha		 = Yii::$app->getSecurity()->generatePasswordHash( $this->txt_senha );
		}

		$this->txt_email = strtolower($this->txt_email);
		    
		return parent::beforeSave( $insert );

	}

	public function afterFind()
	{
		parent::afterFind();

		if ($this->txt_imagem){
			if (!file_exists( Yii::getAlias( '@webroot' ) . str_replace( '@web' , '' , $this->txt_imagem ) )){

				$this->txt_imagem = '@web/img/usuarios/default.jpg';
			} else{

				$this->txt_imagem = $this->txt_imagem;
			}
		} else{
			$this->txt_imagem = '@web/img/usuarios/default.jpg';
		}

	}

	/**
	 * Metodo para atualizacao do contador de acesso do usuario
	 * @return boolean
	 */
	public static function atualizarQtdAcesso()
	{
		$identity	 = Yii::$app->user->identity;
		$model		 = self::find()
			->where( 'cod_usuario=:cod_usuario' , [':cod_usuario' => $identity->cod_usuario] )
			->one();
		$model->qtd_acesso++;

		return $model->save();
	}
	
	
	/**
	 * Metodo para atualizacao ativa ou inativa sessao do usuario
	 * @param boolean $atiIna valor com true ou false
	 * @return boolean
	 */	
	public function ativarInativaSessao( $atiIna ){
		
		if($atiIna==true){
			$this->dte_sessao = date('Y-m-d H:i:s');
			$this->num_ip = \projeto\Util::getClientIP();
			$this->sessao_ativa = true;
		}  else{
			$this->dte_sessao = null;
			$this->num_ip = null;
			$this->sessao_ativa = false;
		}
		$this->save();
		
		return true;
	}

}