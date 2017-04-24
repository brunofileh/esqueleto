<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\TabFuncionalidades;
use app\modules\admin\models\TabFuncionalidadesSearch;
use app\modules\admin\models\TabAcoesSearch;
use app\modules\admin\models\TabPerfisSearch;
use app\modules\admin\models\RlcPerfisFuncionalidadesAcoesSearch;
use app\modules\admin\models\TabMenusSearch;
use app\modules\admin\models\TabModulos;
use projeto\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

/**
 * FuncionalidadesController implements the CRUD actions for TabFuncionalidades model.
 */
class FuncionalidadesController extends Controller
{
	/**
	 * Lists all TabFuncionalidades models.
	 * @return mixed
	 */
	public $activeMenu = 'Módulo';

	public function actionIndex($cod_modulo)
	{
		$searchModel	 = new TabFuncionalidadesSearch();
		$dataProvider	 = $searchModel->searchTabFuncionalidades(Yii::$app->request->queryParams);
		$modulo			 = TabModulos::findOneAsArray(['cod_modulo' => $cod_modulo]);

		$this->titulo	 = 'Gerenciar Funcionalidades';
		$this->subTitulo = "Módulo: {$modulo['txt_nome']}";

		$infoModulo			 = $this->module->info;
		$this->breadcrumbs[] = ['label' => $infoModulo['txt_nome'], 'url' => Url::toRoute($infoModulo['txt_url'])];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Módulos', 'url' => Url::toRoute(['/admin/modulos', 'cod_modulo' => $cod_modulo])];
		$this->breadcrumbs[] = ['label' => $this->titulo];

		return $this->render('index', [
			'searchModel'	 => $searchModel,
			'dataProvider'	 => $dataProvider,
			'modulo'		 => $modulo,
		]);
	}

	/**
	 * Displays a single TabFuncionalidades model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id, $cod_modulo)
	{
		$model	 = $this->findModel($id);
		$modulo	 = TabModulos::findOneAsArray(['cod_modulo' => $cod_modulo]);

		$modelTabMenus = TabMenusSearch::find()->where(['cod_perfil_funcionalidade_acao_fk' => $model->cod_funcionalidade])->one();
		if ($modelTabMenus) {
			$model->lista_menu = $modelTabMenus->dsc_menu;
		}

		$exAcoesPerfis = RlcPerfisFuncionalidadesAcoesSearch::find()->where('cod_funcionalidade_fk = ' . $id . ' and dte_exclusao IS NULL')->all();
		
		if ($exAcoesPerfis) {
			foreach ($exAcoesPerfis as $arr) {
				$acao[]		 = TabAcoesSearch::find()->where(['cod_acao' => $arr->cod_acao_fk])->one()->dsc_acao;
				$perfil[]	 = TabPerfisSearch::find()->where(['cod_perfil' => $arr->cod_perfil_fk])->one()->dsc_perfil;
			}
			$acao				 = array_unique($acao);
			$perfil				 = array_unique($perfil);
			sort($acao);
			sort($perfil);
		
			$model->lista_acao	 = implode(', ', $acao);
			$model->lista_perfil = implode(', ', $perfil);
		}

		$this->titulo	 = 'Detalhar Funcionalidade';
		$this->subTitulo = "Módulo: {$modulo['txt_nome']}";

		$infoModulo			 = $this->module->info;
		$this->breadcrumbs[] = ['label' => $infoModulo['txt_nome'], 'url' => Url::toRoute($infoModulo['txt_url'])];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Módulos', 'url' => Url::toRoute(['/admin/modulos', 'cod_modulo' => $cod_modulo])];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Funcionalidades', 'url' => Url::toRoute(['/admin/funcionalidades', 'cod_modulo' => $cod_modulo])];
		$this->breadcrumbs[] = ['label' => $this->titulo];

		return $this->render('view', [
			'model'	 => $model,
			'modulo' => $modulo,
		]);

	}

	/**
	 * Creates e Updates a new RlcPerfisFuncionalidadesAcoes  model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionAdmin($cod_modulo, $id = null)
	{

		if ($id) {

			$model			 = $this->findModel($id);
			$this->titulo	 = 'Alterar Funcionalidade';
			$acao			 = 'update';
		} else {

			$acao			 = 'create';
			$model			 = new TabFuncionalidadesSearch();
			$this->titulo	 = 'Incluir Funcionalidade';
		}

		$modulo		 = TabModulos::findOneAsArray(['cod_modulo' => $cod_modulo]);
		$listaMenus	 = TabMenusSearch::menusPorModulo($modulo['cod_modulo']);
		
		$this->subTitulo = "Módulo: {$modulo['txt_nome']}";

		if (Yii::$app->request->post()) {

			$model->load(Yii::$app->request->post());
			$transaction = $this->db->beginTransaction();
			try {
				if ($model->save()) {
					
					if ($model->lista_menu) {
						$modelTabMenus										 = TabMenusSearch::find()->where(['cod_menu' => $model->lista_menu])->one();
						$modelTabMenus->cod_perfil_funcionalidade_acao_fk	 = $model->cod_funcionalidade;
						$modelTabMenus->lista_perfil						 = 'lista_perfil';
						$modelTabMenus->save();
					}
					
					$transaction->commit();
					$this->session->setFlashProjeto('success', $acao);
					return $this->redirect(['index', 'cod_modulo' => $modulo['cod_modulo']]);
				}
			} catch (Exception $e) {
				$transaction->rollBack();
				throw $e;
			}
		}

		$infoModulo			 = $this->module->info;
		$this->breadcrumbs[] = ['label' => $infoModulo['txt_nome'], 'url' => Url::toRoute($infoModulo['txt_url'])];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Módulos', 'url' => Url::toRoute(['/admin/modulos', 'cod_modulo' => $cod_modulo])];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Funcionalidades', 'url' => Url::toRoute(['/admin/funcionalidades', 'cod_modulo' => $cod_modulo])];
		$this->breadcrumbs[] = ['label' => $this->titulo];

		return $this->render('admin', [
			'model'		 => $model,
			'modulo'	 => $modulo,
			'listaMenus' => $listaMenus,
		]);
	}


	/**
	 * Deletes an existing TabFuncionalidades model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id, $cod_modulo)
	{
		$model = $this->findModel($id);

		$flag = 0;
		if ($model->rlcPerfisFuncionalidadesAcoes) {
			foreach ($model->rlcPerfisFuncionalidadesAcoes as $value) {
				if ($value->tabRestricoesUsuarios) {
					$flag = 1;
				}
			}
		}

		if ($flag == 1) {
			$this->session->setFlash('danger', 'Erro: Existem Restrições de Usuários vinculadas a esta Funcionalidade.');
		} else {

			RlcPerfisFuncionalidadesAcoesSearch::deleteAll('cod_funcionalidade_fk = ' . $id . '');

			$modelTabMenus = TabMenusSearch::find()->where(['cod_perfil_funcionalidade_acao_fk' => $id])->one();
			if ($modelTabMenus) {
				$modelTabMenus->cod_perfil_funcionalidade_acao_fk	 = null;
				$modelTabMenus->lista_perfil						 = 'lista_perfil';
				$modelTabMenus->save();
			}

			$model->delete();

			$this->session->setFlashProjeto('success', 'delete');
		}

		return $this->redirect(['index', 'cod_modulo' => $cod_modulo]);

	}

	/**
	 * Finds the TabFuncionalidades model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return TabFuncionalidades the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = TabFuncionalidadesSearch::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}

	}

}
