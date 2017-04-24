<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');
defined('YII_ENV_DEV') or define('YII_ENV_DEV', true);

$cfg = \Codeception\Configuration::config()['config'];

defined('YII_TEST_ENTRY_URL') 
	or define('YII_TEST_ENTRY_URL', parse_url($cfg['test_entry_url'], PHP_URL_PATH));
defined('YII_TEST_ENTRY_FILE') 
	or define('YII_TEST_ENTRY_FILE', realpath(dirname(__DIR__) . '/../web/index-test.php'));

require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');

$_SERVER['SCRIPT_FILENAME'] = YII_TEST_ENTRY_FILE;
$_SERVER['SCRIPT_NAME'] = YII_TEST_ENTRY_URL;
$_SERVER['SERVER_NAME'] = parse_url($cfg['test_entry_url'], PHP_URL_HOST);
$_SERVER['SERVER_PORT'] =  parse_url($cfg['test_entry_url'], PHP_URL_PORT) ?: '80';

Yii::setAlias('@app', dirname(realpath(__DIR__ . '/../')));
Yii::setAlias('@tests', dirname(__DIR__));

\Codeception\Util\Autoload::registerSuffix('Steps', __DIR__.DIRECTORY_SEPARATOR.'_steps');
\Codeception\Util\Autoload::registerSuffix('Page', __DIR__.DIRECTORY_SEPARATOR.'_pages');
