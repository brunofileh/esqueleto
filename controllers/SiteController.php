<?php

namespace app\controllers;

use \projeto\web\Controller;
use app\modules\admin\models\VisUsuariosPerfisSearch;
use app\modules\admin\models\TabUsuariosSearch;
use app\modules\admin\models\RlcUsuariosPerfisSearch;
use app\modules\admin\models\TabPerfisSearch;

class SiteController extends Controller
{
	public function actionIndex()
	{
		if (!$this->user->isGuest) {
			return $this->redirect(['/home']);
		}

		return $this->render('index');
	}

	public function actionHome($resposta = null)
	{
		if ($this->user->isGuest) {
			return $this->redirect(['/entrar']);
		}

		if ($resposta) {
			TabUsuariosSearch::atualizarQtdAcesso();

			if ($resposta == 'S') {
				return $this->redirect(['drenagem/usuarios/admin']);
			} else {
                $modulos = VisUsuariosPerfisSearch::getModulosPerfisUsuario($this->user->identity->getId());
                if (count($modulos) > 1) {
                    return $this->redirect(['/home']);
                } else {
                    return $this->redirect(['/'.$modulos[0]['modulo_id']]);
                }
			}
		}
        
        $perfil = RlcUsuariosPerfisSearch::find()
            ->select([TabPerfisSearch::tableName() . '.txt_perfil_prestador'])
            ->innerJoin(TabPerfisSearch::tableName(), TabPerfisSearch::tableName() . '.cod_perfil = ' . RlcUsuariosPerfisSearch::tableName() . '.cod_perfil_fk')
            ->where(['=', 'cod_usuario_fk', $this->user->identity->getId()])
            ->andWhere(RlcUsuariosPerfisSearch::tableName() . '.dte_exclusao IS NULL')
            ->andWhere(TabPerfisSearch::tableName() . '.dte_exclusao IS NULL')
            ->asArray()
            ->one();
        
		$this->titulo = 'Módulos disponíveis';
		return $this->render('home', [
			'modulos' => VisUsuariosPerfisSearch::getModulosPerfisUsuario($this->user->identity->getId()),
            'txt_perfil_prestador' => $perfil['txt_perfil_prestador'],
		]);
	}

	public function actionAtualizaLogin($resposta, $url = null)
	{
		if ($resposta == 'S') {
			$this->user->identity->ativarInativaSessao(true);
		} else {
			session_destroy();
		}
		return $this->redirect($url);
	}

	public function actionAjudaManualPreenchimento()
	{
		$this->titulo = 'Manual de preenchimento';
		return $this->render('ajuda-manual-preenchimento');
	}
}
