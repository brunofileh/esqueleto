<?php

namespace app\modules\financeiro\controllers;

use Yii;
use projeto\web\Controller;

class InicioController extends Controller {

    public function actionIndex() {
     
        return $this->render('index');
    }

}
