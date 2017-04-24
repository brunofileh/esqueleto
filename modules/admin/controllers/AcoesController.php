<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\TabAcoes;
use app\modules\admin\models\TabAcoesSearch;
use projeto\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * AcoesController implements the CRUD actions for TabAcoes model.
 */
class AcoesController extends Controller
{

	/**
	 * Lists all TabAcoes models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new TabAcoesSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$this->titulo	 = 'Gerenciar Ações';
		
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single TabAcoes model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$this->titulo	 = 'Detalhar Ações';
		
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new TabAcoes model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new TabAcoes();
		
		$this->titulo	 = 'Incluir Ações';
				
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$this->session->setFlashProjeto('success', 'create');
			return $this->redirect(['view', 'id' => $model->cod_acao]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing TabAcoes model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		$this->titulo	 = 'Alterar Ações';
				
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$this->session->setFlashProjeto('success', 'update');
			return $this->redirect(['view', 'id' => $model->cod_acao]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing TabAcoes model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{		
		$model = $this->findModel($id);
		$msg = array();
		if($model->rlcPerfisFuncionalidadesAcoes) {
			$msg[] = 'funcionalidades';
		}
		
		if ($msg) {
			$msg = 'Erro: Existem '.implode(", ", $msg).' vinculadas a esta Ação.';
			$this->session->setFlash('danger', $msg);
		}
		else {
			$model->dte_exclusao = 'now()';
			$model->save();
			$this->session->setFlashProjeto('success', 'delete');
		}
		
		return $this->redirect(['index']);
	}

	/**
	 * Finds the TabAcoes model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return TabAcoes the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = TabAcoes::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
