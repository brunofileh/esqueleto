<?php

namespace mgcode\assets;

use yii\base\InvalidConfigException;
use yii\web\AssetBundle;

/**
 * @link https://github.com/mg-code/yii2-assets
 * @author Maris Graudins <maris@mg-interactive.lv>
 */
class IntlJsAsset extends AssetBundle
{
    public $sourcePath = '@bower/intl/dist';
    public $js = ['Intl.min.js'];

    public function init()
    {
        parent::init();
        if(!file_exists(\Yii::getAlias('@bower/intl/dist/Intl.min.js'))) {
            throw new InvalidConfigException('You must include `bower-asset/intl` package in your composer.json configuration file.');
        }
    }
}