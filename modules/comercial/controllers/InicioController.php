<?php

namespace app\modules\comercial\controllers;

use Yii;
use projeto\web\Controller;

class InicioController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
