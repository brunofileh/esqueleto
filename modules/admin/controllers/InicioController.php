<?php

namespace app\modules\admin\controllers;

class InicioController extends \projeto\web\Controller {

    public function actionIndex() {
        $this->titulo = 'Controle de Acesso';
        $this->subTitulo = 'Módulo gerenciador para controle de acesso do usuário';
        return $this->render('index');
    }

}
