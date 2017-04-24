<?php

namespace mgcode\assets;

use yii\base\InvalidConfigException;
use yii\web\AssetBundle;

/**
 * @link https://github.com/mg-code/yii2-assets
 * @author Maris Graudins <maris@mg-interactive.lv>
 */
class HistoryJsAsset extends AssetBundle
{
    public $sourcePath = '@bower/history.js/scripts/bundled/html5';
    public $js = [
        'native.history.js',
    ];

    public function init()
    {
        parent::init();
        if(!file_exists(\Yii::getAlias('@bower/history.js/scripts/bundled/html5/native.history.js'))) {
            throw new InvalidConfigException('You must include `bower-asset/history.js` package in your composer.json configuration file.');
        }
    }
}