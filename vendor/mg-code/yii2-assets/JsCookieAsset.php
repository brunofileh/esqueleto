<?php

namespace mgcode\assets;

use yii\base\InvalidConfigException;
use yii\web\AssetBundle;

/**
 * @link https://github.com/mg-code/yii2-assets
 * @author Maris Graudins <maris@mg-interactive.lv>
 */
class JsCookieAsset extends AssetBundle
{
    public $sourcePath = '@bower/js-cookie/src';
    public $js = ['js.cookie.js'];

    public function init()
    {
        parent::init();
        if(!file_exists(\Yii::getAlias('@bower/js-cookie/src/js.cookie.js'))) {
            throw new InvalidConfigException('You must include `bower-asset/js-cookie` package in your composer.json configuration file.');
        }
    }
}