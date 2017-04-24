<?php

namespace mgcode\assets;

use yii\base\InvalidConfigException;
use yii\web\AssetBundle;

/**
 * @link https://github.com/mg-code/yii2-assets
 * @author Maris Graudins <maris@mg-interactive.lv>
 */
class SlyAsset extends AssetBundle
{
    public $sourcePath = '@bower/sly/dist';
    public $js = [
        'sly.min.js',
    ];

    public function init()
    {
        parent::init();
        if(!file_exists(\Yii::getAlias('@bower/sly/dist/sly.min.js'))) {
            throw new InvalidConfigException('You must include `bower-asset/sly` package in your composer.json configuration file.');
        }
    }
}