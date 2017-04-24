<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\TabMenus;
use app\modules\admin\models\TabMenusSearch;
use app\modules\admin\models\TabModulosSearch;
use app\modules\admin\models\TabPerfisSearch;
use app\modules\admin\models\RlcMenusPerfisSearch;
use app\modules\admin\models\VisMenusPerfisSearch;
use projeto\web\Controller;
use yii\base\Exception;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * MenusController implements the CRUD actions for TabMenus model.
 */
class MenusController extends Controller
{

	public $activeMenu = 'Módulo';
	/**
	 * Lists all TabMenus models.
	 * @return mixed
	 */
	public function actionIndex($cod_modulo)
	{
		$modulo = TabModulosSearch::findOneAsArray(['cod_modulo' => $cod_modulo]);

		$searchModel				= new TabMenusSearch();
		$searchModel->cod_modulo_fk = $cod_modulo;

		$dataProvider = $searchModel->searchMenuModulo(Yii::$app->request->queryParams);

		$this->titulo	= 'Gerenciar Menus';
		$this->subTitulo = "Módulo: {$modulo['txt_nome']}";

		$this->breadcrumbs[] = ['label' => 'Controle de Acesso' , 'url' => Url::toRoute( '/admin' )];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Módulos' , 'url' => Url::toRoute( ['/admin/modulos', 'cod_modulo' => $cod_modulo] )];
		$this->breadcrumbs[] = ['label' =>  $this->titulo];
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'modulo'	   => $modulo
		]);
	}

	/**
	 * Displays a single TabMenus model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id, $cod_modulo)
	{
		$model = $this->findModel($id);

		$modulo = TabModulosSearch::findOneAsArray(['cod_modulo' => $model->rlcMenusPerfis[0]->tabPerfis->cod_modulo_fk]);

		if ($model->rlcMenusPerfis) {

			foreach ($model->rlcMenusPerfis as $perfil) {
				$pefis[] = $perfil->tabPerfis->txt_nome;
			}
			sort($pefis);

			$model->lista_perfil = implode(', ', $pefis);
		}

		$this->titulo	= 'Detalhar Menu';
		$this->subTitulo = "Módulo: {$modulo['txt_nome']}";

		$this->breadcrumbs[] = ['label' => 'Controle de Acesso' , 'url' => Url::toRoute( '/admin' )];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Módulos' , 'url' => Url::toRoute( ['/admin/modulos', 'cod_modulo' => $cod_modulo] )];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Menus' , 'url' => Url::toRoute( ['/admin/menus', 'cod_modulo' => $cod_modulo] )];
		$this->breadcrumbs[] = ['label' =>  $this->titulo];
		
		return $this->render('view', [
			'model'  => $model,
			'modulo' => $modulo
		]);
	}
		
	/**
	 * Creates e Updates a new TabMenus  model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionAdmin($cod_modulo, $id = null)
	{
		if ($id) {

			$model = $this->findModel($id);

			$perfilMenus = VisMenusPerfisSearch::findAllAsArray(['cod_menu_fk' => $model->cod_menu]);

			if ($perfilMenus) {

				foreach ($perfilMenus as $menuPerfil) {

					$arrayMenuPerfil[] = $menuPerfil['cod_perfil_fk'];
				}
			}

			$model->lista_perfil = Json::encode($arrayMenuPerfil);

			$listaPerfis = TabPerfisSearch::find()->where(['cod_modulo_fk' => $perfilMenus[0]['cod_modulo_fk']]);

			$listaMenusPai = VisMenusPerfisSearch::find()->where(
					"cod_menu_fk <> {$model->cod_menu} AND
					 cod_modulo_fk =  {$perfilMenus[0]['cod_modulo_fk']}"
				)->all();

			$modulo = TabModulosSearch::findOne(['cod_modulo' => $perfilMenus[0]['cod_modulo_fk']]);

			$acao			= 'update';
			$this->titulo	= 'Alterar Menu';
			$this->subTitulo = "Módulo: {$modulo['txt_nome']}";
		} else {

			$model = new TabMenusSearch();

			$modulo = TabModulosSearch::findOne(['cod_modulo' => $cod_modulo]);

			$listaPerfis	 = TabPerfisSearch::find()->where(['cod_modulo_fk' => $cod_modulo]);
			$arrayMenuPerfil = [];
			$listaMenusPai   = VisMenusPerfisSearch::find()->where(
					['cod_modulo_fk' => $cod_modulo]
				)->orderBy(
					['num_ordem' => SORT_ASC]
				)->all();

			$acao = 'create';

			$this->titulo	= 'Incluir Menu';
			$this->subTitulo = "Módulo: {$modulo['txt_nome']}";
		}

		if ($model->load(Yii::$app->request->post())) {

			$transaction = $this->db->beginTransaction();
			try {
				if( ! $model->txt_url){
					$model->txt_url = '/';
				}
				if ($model->save() && $model->lista_perfil) {

					$lista = (Json::decode($model->lista_perfil)) ? Json::decode($model->lista_perfil) : [];

					if (array_diff_assoc($arrayMenuPerfil, $lista) || array_diff_assoc($lista, $arrayMenuPerfil)) {

						RlcMenusPerfisSearch::deleteAll([ 'cod_menu_fk' => $model->cod_menu]);

						foreach (Json::decode($model->lista_perfil) as $perfil) {

							$modelPerfil				= new RlcMenusPerfisSearch();
							$modelPerfil->cod_perfil_fk = $perfil;
							$modelPerfil->cod_menu_fk   = $model->cod_menu;

							if (!$modelPerfil->save()) {

								throw new Exception('Erro ao Inserir');
							}
						}
					}
					$transaction->commit();

					$this->session->setFlashProjeto('success', 'create');

					return $this->redirect(['index', 'cod_modulo' => $cod_modulo]);
				}
			} catch (Exception $e) {
				$this->session->setFlash('danger', $e->getMessage());
				$transaction->rollBack();
			}
		}
		
		$this->breadcrumbs[] = ['label' => 'Controle de Acesso' , 'url' => Url::toRoute( '/admin' )];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Módulos' , 'url' => Url::toRoute( ['/admin/modulos', 'cod_modulo' => $cod_modulo] )];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Menus' , 'url' => Url::toRoute( ['/admin/menus', 'cod_modulo' => $cod_modulo] )];
		$this->breadcrumbs[] = ['label' =>  $this->titulo];

		return $this->render('admin', [
			'model'		 => $model,
			'modulo'		=> $modulo,
			'listaPerfis'   => $listaPerfis,
			'listaMenusPai' => $listaMenusPai
		]);
	}

	/**
	 * Deletes an existing TabMenus model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$msg		   = [];
		$model		 = $this->findModel($id);
		$cod_modulo_fk = $model->rlcMenusPerfis[0]->tabPerfis->cod_modulo_fk;

		// if ($model->rlcPerfisFuncionalidadesAcoes) {

		//	 $msg[] .= 'funcionalidades';
		// }

		if ($model->tabMenus) {

			$msg[] = 'menus filhos';
		}

		if (false) {

			$msg = 'Erro: Existem ' . implode(", ", $msg) . ' vinculados ao menu.';
			$this->session->setFlash('danger', $msg);
		} else {

			$transaction = $this->db->beginTransaction();
			try {

				if (RlcMenusPerfisSearch::deleteAll(['cod_menu_fk' => $model->cod_menu]) && $model->delete()) {

					$transaction->commit();
					$this->session->setFlashProjeto('success', 'delete');
				} else {

					$transaction->rollBack();
					$this->session->setFlashProjeto('danger', 'delete');
				}
			} catch (Exception $e) {

				$transaction->rollBack();
				$this->session->setFlash('danger', $e->getMessage());
			}
		}
		return $this->redirect(['index', 'cod_modulo' => $cod_modulo_fk]);
	}

	/**
	 * Finds the TabMenus model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return TabMenus the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = TabMenusSearch::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

}
