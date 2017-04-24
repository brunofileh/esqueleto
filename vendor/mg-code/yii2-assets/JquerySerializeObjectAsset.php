<?php

namespace mgcode\assets;

use yii\base\InvalidConfigException;
use yii\web\AssetBundle;

/**
 * @link https://github.com/mg-code/yii2-assets
 * @author Maris Graudins <maris@mg-interactive.lv>
 */
class JquerySerializeObjectAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-serialize-object/dist';
    public $js = [
        'jquery.serialize-object.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        parent::init();
        if(!file_exists(\Yii::getAlias('@bower/jquery-serialize-object/dist/jquery.serialize-object.min.js'))) {
            throw new InvalidConfigException('You must include `bower-asset/jquery-serialize-object` package in your composer.json configuration file.');
        }
    }
}