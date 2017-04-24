<?php

$params = require(__DIR__ . '/params-' . YII_ENV . '.php');
$db = require(__DIR__ . '/db-' . YII_ENV . '.php');

$config = [
	'id'		 => 'sisscm',
	'version'	 => '2017.003',
	'name'		 => 'SCM Engenharia',
	'charset'	 => 'UTF-8',
	'language'	 => 'pt-BR',
	'timeZone'	 => 'America/Sao_Paulo',
	'basePath'	 => dirname( __DIR__ ),
	'bootstrap'	 => [
		[
			'class' => '\mgcode\sessionWarning\components\SessionWarningBootstrap',
			'initMessages' => true,
		],
		'log',
	],
	'controllerMap' => [
		'session-warning' => [
			'class' => 'mgcode\sessionWarning\controllers\SessionWarningController',
		],
	],
	'modules'	 => [
		// projeto/admin - Módulo de Administração do sistema
		'admin'	 => ['class' => 'app\modules\admin\Module'],
		// projeto/gestao - Módulo de Gestão Municipal
		// Grid View
		'gridview' => ['class' => 'kartik\grid\Module'],
	],
	'aliases' => [
		'@projeto' => '@app/projeto',
	],
	'components' => [
		'view' => ['class' => 'projeto\web\View'],
		'reCaptcha' => [
			'name' => 'reCaptcha',
			'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
			'siteKey' => '6LeB2R8TAAAAAHvTGoLMoeCaDvR10ZLsi7SvSCB6',
			'secret' => '6LeB2R8TAAAAACoGq6XXmxOjo-PC1Nu6hj71yXJX',
		],
		'session' => [
			// gerenciamento da sessão via banco de dados
			'class' => 'projeto\web\DbSession',
			// nome do componente responsável
			'db' => 'db',
			// session_name: diferencia os nomes para não gerar conflitos
			'name' => ('projeto_' . YII_ENV),
			// nome da tabela de gerenciamento da sessão. 
			'sessionTable' => 'acesso.tab_sessao_php',
			'writeCallback' => function($session) {
				return ['user_id' => Yii::$app->user->id];
			},
			/**
			 * The number of seconds after which data will be seen as 'garbage' and cleaned up. 
			 * The default value is 1440 seconds 
			 * (or the value of "session.gc_maxlifetime" set in php.ini).
			 */
			'timeout'  => 2400,
		],
		'urlManager' => [
			/**
			 * true =>  //localhost/projeto[/index.php]/modulo/controller/action?id=10
			 * false => //localhost/projeto[/index.php]?r=/modulo/controller/action&id=10
			 */
			'enablePrettyUrl' => true,
			// mostra ou esconde o [index.php]
			'showScriptName' => false,
			// regras específicas
			'rules'				 => [
				'sobre' => 'site/sobre',
				'home' => 'site/home',
				'entrar' => 'admin/login',
				'sair' => 'admin/login/logout',
				'register' => 'admin/login/register',
				'recuperar-senha' => 'admin/login/recuperar-senha',
				'alterar-senha' => 'drenagem/minha-conta/update',
			]
		],
		'assetManager' => [
			'forceCopy' => (YII_ENV == 'dev'),
			'bundles' => [
				'@vendor\dmstr\yii2-adminlte-assetweb\AdminLteAsset' => [
					'skin' => 'skin-blue-light',
					 'forceCopy' => (YII_ENV == 'dev'),
				],
			],
		],
		'formatter' => [
			'dateFormat'		=> 'php:d/m/Y',
			'datetimeFormat'	=> 'php:d/m/Y H:i:s',
			'defaultTimeZone'	=> 'America/Sao_Paulo',
			'decimalSeparator'	=> ',',
			'thousandSeparator'	=> '.',
			'currencyCode'		=> 'BRL',
		],
		'request' => [
			'cookieValidationKey'	 => 'A1b@-2y%G-w*F5-!C4r',
			'enableCsrfValidation'	 => true,
		],
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'user' => [
			'identityClass' => 'app\modules\admin\models\MdlUsuarios',
			'enableAutoLogin' => false,
			'loginUrl' => ['/entrar']
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'mailer' => [
			'class' => 'projeto\swiftmailer\Mailer',
			'useFileTransport' => false,
			'transport' => [
				'class'		=> 'Swift_SmtpTransport',
				'host'		=> (YII_ENV == 'dev') ? 'smtp.gmail.com' : 'null',
				'username'	=> (YII_ENV == 'dev') ? 'brunofileh@gmail.com' 	: null,
				'password'	=> (YII_ENV == 'dev') ? 'Khumba@7' 						: null,
				'port' 		=> (YII_ENV == 'dev') ? '465'								: 25,
				'encryption'=> (YII_ENV == 'dev') ? 'ssl'								: null,
			],
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			// 'flushInterval' => 1000,
			'targets' => [
				'file' => [
					'class'	=> 'yii\log\FileTarget',
					// 'categories' => ['yii\web\HttpException:404'],
					'levels' => ['error', 'warning'],
				],
				'email' => [
					'class' => 'yii\log\EmailTarget',
					'enabled' => (YII_ENV == 'test' || YII_ENV == 'prod'),
					'levels' => ['error', 'warning'],
					'message' => [
						'from' => 'brunofileh@gmail.com', 
						'to' => 'brunofileh@gmail.com',
						'subject' => 'Erros SCM - ' . YII_ENV,
					],
				],
			],
		],
		'db' => $db,
	],
	'params' => $params,
	
	// EVENTOS
	// 1. Carrega os parâmetros do banco de dados para o sistema
	'on beforeRequest' => function () use ($params) {
		
		// timeout de acordo com perfil
		if (Yii::$app->user->identity !== null && Yii::$app->user->identity->cod_prestador_fk === null) {
			Yii::$app->session->timeout = 7100;
		}
				
		// -- auditoria -- //
		$ip = \projeto\Util::getClientIP();
		Yii::$app->db->createCommand("SET application_name = '{$ip}'")->execute();

		// -- parametros do sistema -- //
                
		$getParametros = function () use ($params) {
			return \app\models\TabParametros::find()
				->where(['num_ano_ref' => $params['ano-ref']])
				->with(['tabModulos' => function (\yii\db\ActiveQuery $query) {
					$query->select(['id', 'cod_modulo']);
				}])
				->asArray()
				->all();
		};
		
		if ($params['habilitar-cache-global']) {
			$cacheKey = ['parametros-do-sistema'];
			if (($dbParams = \Yii::$app->cache->get($cacheKey)) === false) {
				$dbParams = $getParametros();
				\Yii::$app->cache->set($cacheKey, $dbParams);
			}
		}
		else {
			$dbParams = $getParametros();
		}
		
                
		foreach ($dbParams as $param) {
			if (isset($param['modulo_fk'])) {
				// Paramêtros de módulo
				$params[$param['tabModulos']['id']][$param['sgl_parametro']] = $param['vlr_parametro'];
			}
			elseif (!isset($params[$param['sgl_parametro']])) {
				// Paramêtros de sistema
				$params[$param['sgl_parametro']] = $param['vlr_parametro'];
			}
		}
                Yii::$app->params = \yii\helpers\ArrayHelper::merge(Yii::$app->params, $params);
	},
];

// ajustes para o ambiente de desenvolvimento somente (dev)
if (YII_ENV == 'dev') {
	// modulo de debug
	$config['bootstrap'][]		 = 'debug';
	$config['modules']['debug']	 = [
		'class' => 'yii\debug\Module',
	];
	// modulo de geração de código
	$config['bootstrap'][]		 = 'gii';
	$config['modules']['gii']	 = [
		'class'		 => 'yii\gii\Module',
		'controllerNamespace' => 'projeto\gii\controllers',
		'generators' => [
			'model'		 => [
				'class'		 => 'projeto\gii\generators\model\Generator',
				'templates'	 => ['projeto' => '@app/projeto/gii/generators/model/projeto', 'projeto_coleta' => '@app/projeto/gii/generators/model/projeto_coleta']
			],
			'module'	 => [
				'class'		 => 'projeto\gii\generators\module\Generator',
				'templates'	 => ['projeto' => '@app/projeto/gii/generators/module/projeto']
			],
			'controller' => [
				'class' => 'projeto\gii\generators\controller\Generator',
			//'templates' => ['projeto-crud' => '@app/projeto/gii/crud']
			],
			'crud'		 => [
				'class'		 => 'projeto\gii\generators\crud\Generator',
				'templates'	 => ['projeto-crud' => '@app/projeto/gii/generators/crud/projeto', 'projeto_coleta' => '@app/projeto/gii/generators/crud/projeto_coleta']
			]
		]
	];
}

return $config;
