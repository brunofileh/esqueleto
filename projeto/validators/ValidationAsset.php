<?php

namespace projeto\validators;

class ValidationAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@projeto/validators/assets';
    public $js = [
        'projeto.validation.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
