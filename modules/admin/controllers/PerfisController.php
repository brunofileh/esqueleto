<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\TabPerfis;
use app\modules\admin\models\TabPerfisSearch;
use projeto\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\admin\models\TabModulosSearch;
use yii\helpers\Url;

/**
 * PerfisController implements the CRUD actions for TabPerfis model.
 */
class PerfisController extends Controller {

	/**
	 * Lists all TabPerfis models.
	 * @return mixed
	 */
	public $activeMenu = 'Módulo';

	public function actionIndex($cod_modulo) {

		$searchModel = new TabPerfisSearch();

		$modulo = TabModulosSearch::findOneAsArray(['cod_modulo' => $cod_modulo]);

		$searchModel->cod_modulo_fk = $cod_modulo;
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$this->titulo = 'Gerenciar Perfis';
		$this->subTitulo = "Módulo: {$modulo['txt_nome']}";

		$this->breadcrumbs[] = ['label' => 'Controle de Acesso', 'url' => Url::toRoute('/admin')];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Módulos', 'url' => Url::toRoute(['/admin/modulos', 'cod_modulo' => $cod_modulo])];
		$this->breadcrumbs[] = ['label' => $this->titulo];

		return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'modulo' => $modulo
		]);
	}

	/**
	 * Displays a single TabPerfis model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id) {

		$model = $this->findModel($id);

		$modulo = TabModulosSearch::findOneAsArray(['cod_modulo' => $model->cod_modulo_fk]);

		$this->titulo = 'Detalhar Perfil';
		$this->subTitulo = "Módulo: {$modulo['txt_nome']}";

		$this->breadcrumbs[] = ['label' => 'Controle de Acesso', 'url' => Url::toRoute('/admin')];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Módulos', 'url' => Url::toRoute(['/admin/modulos', 'cod_modulo' => $model->cod_modulo_fk])];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Perfis', 'url' => Url::toRoute(['/admin/perfis', 'cod_modulo' => $model->cod_modulo_fk])];
		$this->breadcrumbs[] = ['label' => $this->titulo];

		return $this->render('view', [
				'model' => $model,
				'modulo' => $modulo
		]);
	}

	/**
	 * Creates e Updates a new TabPerfis  model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionAdmin($cod_modulo, $id = null) {

		if ($id) {

			$model = $this->findModel($id);
			$modulo = TabModulosSearch::findOneAsArray(['cod_modulo' => $model->cod_modulo_fk]);

			$acao = 'update';
			$this->titulo = 'Alterar Perfil';
			$this->subTitulo = "Módulo: {$modulo['txt_nome']}";
		} else {
			$model = new TabPerfis();
			$modulo = TabModulosSearch::findOneAsArray(['cod_modulo' => $cod_modulo]);

			$acao = 'create';
			$this->titulo = 'Incluir Perfil';
			$this->subTitulo = "Módulo: {$modulo['txt_nome']}";

			$model->cod_modulo_fk = $cod_modulo;
		}

		$this->breadcrumbs[] = ['label' => 'Controle de Acesso', 'url' => Url::toRoute('/admin')];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Módulos', 'url' => Url::toRoute(['/admin/modulos', 'cod_modulo' => $model->cod_modulo_fk])];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Perfis', 'url' => Url::toRoute(['/admin/perfis', 'cod_modulo' => $model->cod_modulo_fk])];
		$this->breadcrumbs[] = ['label' => $this->titulo];

		if ($model->load(Yii::$app->request->post()) && $model->save()) {

			$this->session->setFlashProjeto('success', $acao);
			return $this->redirect(['view', 'id' => $model->cod_perfil]);
		}

		return $this->render('admin', [
				'model' => $model,
				'modulo' => $modulo
		]);
	}

	/**
	 * Deletes an existing TabPerfis model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id) {
		$msg = [];
		$model = $this->findModel($id);

		if ($model->rlcMenusPerfis) {

			$msg[] = 'menus';
		}

		if ($model->rlcPerfisFuncionalidadesAcoes) {

			$msg[] .= 'funcionalidades';
		}

		if ($model->rlcUsuariosPerfis) {

			$msg[] = 'usuários';
		}

		if ($msg) {

			$msg = 'Erro: Existem ' . implode(", ", $msg) . ' vinculados ao perfil.';
			$this->session->setFlash('danger', $msg);
		} else {
			if ($model->delete()) {

				$this->session->setFlashProjeto('success', 'delete');
			} else {

				$this->session->setFlashProjeto('danger', 'delete');
			}
		}

		return $this->redirect(['index', 'cod_modulo' => $model->cod_modulo_fk]);
	}

	/**
	 * Finds the TabPerfis model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return TabPerfis the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = TabPerfisSearch::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	public function actionBuscaPerfisModulo() {
		$cod_modulo = Yii::$app->request->post()['dados'];

		$perfis = TabPerfisSearch::find()->where(['cod_modulo_fk' => $cod_modulo])->asArray()->orderBy('txt_nome')->all();
		$perfisSessao = (\yii::$app->session->get('perfil-modulo')) ? \yii::$app->session->get('perfil-modulo') : [];
		if ($perfis) {
			echo "<option value=''> -- selecione -- </option>";
			foreach ($perfis as $key => $perfil) {
				if (!array_key_exists($perfil['cod_perfil'], $perfisSessao)) {
					echo "<option value='" . $perfil['cod_perfil'] . "'>" . $perfil['txt_nome'] . "</option>";
				}
			}
		} else {
			echo "<option></option>";
		}
	}

	public function actionAdicionaPerfisModulo() {

		$post = Yii::$app->request->post();
		$msg = [];
		if ($post['cod_perfil']) {

			$perfis = (\yii::$app->session->get('perfil-modulo')) ? \yii::$app->session->get('perfil-modulo') : [];

			if ($perfis) {
				foreach ($perfis as $key => $value) {
					if ($value['cod_modulo'] == $post['cod_modulo']) {
						$msg['tipo'] = 'error';
						$msg['msg'] = 'Já existe perfil para esse módulo.';
						$msg['icon'] = 'ban';
						break;
					}
				}
			}
			if (!$msg) {
				if (!array_key_exists($post['cod_perfil'], $perfis)) {

					$perfil = TabPerfisSearch::find()->where(['cod_perfil' => $post['cod_perfil']])->one();
					$perfis[$post['cod_perfil']]['cod_perfil'] = $post['cod_perfil'];
					$perfis[$post['cod_perfil']]['cod_modulo'] = $post['cod_modulo'];
					$perfis[$post['cod_perfil']]['txt_perfil'] = $perfil->txt_nome;
					$perfis[$post['cod_perfil']]['txt_modulo'] = $perfil->tabModulos->txt_nome;


					\yii::$app->session->set('perfil-modulo', $perfis);


					$msg['tipo'] = 'success';
					$msg['msg'] = 'Inclusão efetivada com sucesso.';
					$msg['icon'] = 'check';
				} else {
					$msg['tipo'] = 'error';
					$msg['msg'] = 'Perfil já cadastrado.';
					$msg['icon'] = 'ban';
				}
			}
		} else {
			$msg['tipo'] = 'error';
			$msg['msg'] = 'Erro é necessario escolhar um módulo e um perfil.';
			$msg['icon'] = 'ban';
		}
		Yii::$app->controller->action->id = 'index';

		$dados = ['form' => $this->renderAjax('/perfis/_grid', ['msg' => $msg])];

		return \yii\helpers\Json::encode($dados);
	}

	public function actionExcluiPerfisModulo($cod_perfil) {

		$perfis = \yii::$app->session->get('perfil-modulo');

		unset($perfis[$cod_perfil]);

		\yii::$app->session->set('perfil-modulo', $perfis);


		$msg['tipo'] = 'success';
		$msg['msg'] = 'Exclusão efetivada com sucesso.';
		$msg['icon'] = 'check';

		Yii::$app->controller->action->id = 'index';

		$dados = ['form' => $this->renderAjax('/perfis/_grid', ['msg' => $msg])];

		return \yii\helpers\Json::encode($dados);
	}

}
