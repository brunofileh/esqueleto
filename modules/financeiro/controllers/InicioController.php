<?php

namespace app\modules\financeiro\controllers;

use Yii;
use projeto\web\Controller;

class InicioController extends Controller {

    public function actionIndex() {
        print_r('\projeto\web\Controller'); exit;
        return $this->render('index');
    }

}
