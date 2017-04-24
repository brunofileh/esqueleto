<?php

namespace app\assets;
use yii\web\AssetBundle;

class Base extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];
    public $depends = [];
    
    public function init()
    {
        parent::init();
        
        foreach ($this->css as &$css) {
            $css .= '?' . \Yii::$app->version;
        }
        foreach ($this->js as &$js) {
            $js .= '?' . \Yii::$app->version;
        }
    }
}
