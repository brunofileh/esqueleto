<?php

function isHttps() {
	return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
}

if (isHttps()) {
	header('HTTP/1.1 301 Moved Permanently');
	header("Location: http://{$_SERVER['SERVER_NAME']}{$_SERVER['SCRIPT_NAME']}");
	exit();
}

// dev=local | test=homologação | prod=produção
defined('YII_ENV') or define('YII_ENV', 'dev');

(YII_ENV == 'dev') ? define('YII_DEBUG', true) : define('YII_DEBUG', false);

$config = require(__DIR__ . '/../config/web.php');

ini_set('log_errors', $config['params']['php-log-erros']);
error_reporting($config['params']['php-error-reporting']);
mb_internal_encoding($config['charset']);

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

(new yii\web\Application($config))->run();
