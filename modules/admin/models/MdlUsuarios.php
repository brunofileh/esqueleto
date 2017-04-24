<?php

namespace app\modules\admin\models;

use yii\base\NotSupportedException;
use yii\helpers\Security;
use yii\web\IdentityInterface;
use app\modules\admin\models\TabUsuariosSearch;

class MdlUsuarios extends TabUsuariosSearch implements IdentityInterface
{
    /*
    // implementado na classe pai
    const SCENARIO_LOGIN='login';
    const SCENARIO_DEFAULT='default';
    */
    protected $modulo;

    public function validarEmailSenha($attribute, $params)
    {
        $user = $this->getUser();
    
        if(!$user) {
            $this->addError('txt_email', 'Endereço de e-mail não cadastrado.');
        }
        elseif ($user && $user->txt_ativo == 'N'){
            $this->addError('txt_email', 'Usuário inativo, favor entrar em contato com a área de suporte.');
        }
        elseif ($user && !\Yii::$app->getSecurity()->validatePassword($this->txt_senha, $user->txt_senha)) {

			$this->addError('txt_senha', 'Senha incorreta.');
        }
    }
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['login'] = ['txt_email', 'txt_senha'];
        return $scenarios;
    }

    public function login()
    {
        $this->scenario = 'login';

		if ($this->validate()) {
            return \Yii::$app->user->login($this->getUser(), 0);
        }
        return false;
    }

    public function getUser()
    {
        static $_user=null;

        if (is_null($_user)) {
            $_user = self::findByLogin($this->txt_login);
        }
			if( ! $_user ){
				$this->addError( 'txt_login' , 'Usuário não encontrado' );
			}

        return $_user;
    }

	public function setModulo($modulo)
    {
        $this->modulo = $modulo;
    }
	
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        // return static::findOne(['access_token' => $token]);
    }
    public static function findByLogin($login)
    {
	
		return static::find()->where("(lower(txt_login)=:login OR "
			. "replace (replace (num_cpf, '-',''),'.','')=:cpf OR "
			. "replace (replace (replace (txt_login, '-',''),'.',''),'/','')=:login) AND "
			. "txt_ativo=:ativo", 
			[':cpf'=>  str_replace(['-','.'], '' , $login), ':login'=>strtolower($login), ':ativo'=>'S'])->one();
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
    public function generateAuthKey()
    {
        $this->authKey = Security::generateRandomKey();
    }
}
