<?php

namespace mgcode\assets;

use yii\base\InvalidConfigException;
use yii\web\AssetBundle;

/**
 * By default it uses locale defined in Formatter configuration.
 * If you want to override it you can define it in application `params` section:
 * ~~~
 * return [
 *     ..... application configuration ....
 *     'params' => [
 *          'intlJsLocale' => 'en-US',
 *          ...
 *      ]
 * ]
 * ~~~
 * @link https://github.com/mg-code/yii2-assets
 * @author Maris Graudins <maris@mg-interactive.lv>
 */
class IntlJsLocaleAsset extends AssetBundle
{
    public $sourcePath = '@bower/intl/locale-data/jsonp';
    public $js = [];
    public $depends = [
        'mgcode\assets\IntlJsAsset'
    ];

    public function init()
    {
        parent::init();
        if(!is_dir(\Yii::getAlias('@bower/intl/locale-data/jsonp'))) {
            throw new InvalidConfigException('You must include `bower-asset/intl` package in your composer.json configuration file.');
        }
        $locale = isset(\Yii::$app->params['intlJsLocale']) ? \Yii::$app->params['intlJsLocale'] : \Yii::$app->formatter->locale;
        $this->js[] = $locale.'.js';
    }
}