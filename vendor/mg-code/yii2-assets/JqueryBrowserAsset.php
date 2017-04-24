<?php

namespace mgcode\assets;

use yii\base\InvalidConfigException;
use yii\web\AssetBundle;

/**
 * @link https://github.com/mg-code/yii2-assets
 * @author Maris Graudins <maris@mg-interactive.lv>
 */
class JqueryBrowserAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery.browser/dist';
    public $js = [
        'jquery.browser.min.js',
    ];

    public function init()
    {
        parent::init();
        if(!file_exists(\Yii::getAlias('@bower/jquery.browser/dist/jquery.browser.min.js'))) {
            throw new InvalidConfigException('You must include `bower-asset/jquery.browser` package in your composer.json configuration file.');
        }
    }
}