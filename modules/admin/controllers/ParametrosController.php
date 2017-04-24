<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\TabParametros;
use app\models\TabParametrosSearch;
use projeto\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ParametrosController implements the CRUD actions for TabParametros model.
 */
class ParametrosController extends Controller
{
	/**
	 * Lists all TabParametros models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$params = Yii::$app->request->queryParams;
		if (!$this->request->get('TabParametrosSearch')['num_ano_ref']) {
			$params['TabParametrosSearch']['num_ano_ref'] = Yii::$app->params['ano-ref'];
		}
		
		if (null === $this->session->get('parametros.filtro') || count($params['TabParametrosSearch']) > 1) {
			$this->session->set('parametros.filtro', $params);
		}

		$searchModel = new TabParametrosSearch();
		$dataProvider = $searchModel->search($this->session->get('parametros.filtro'));
		$dataProvider->query->orderBy(['modulo_fk' => SORT_ASC, 'num_ano_ref' => SORT_ASC, 'sgl_parametro' => SORT_ASC]);
		
		$this->titulo = 'Gerenciar parâmetros';
		$this->subTitulo = '';

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single TabParametros model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$this->titulo = 'Detalhar parâmetro';
		$this->subTitulo = '';
			
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new TabParametros model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new TabParametros();

		$this->titulo = 'Incluir parâmetro';
		$this->subTitulo = '';
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$this->session->setFlashProjeto( 'success', 'update' );
			return $this->redirect(['view', 'id' => $model->cod_parametro]);
		}
		else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing TabParametros model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
	
		$this->titulo = 'Alterar parâmetro';
		$this->subTitulo = '';
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$this->session->setFlashProjeto( 'success', 'update' );
			return $this->redirect(['index']);
		}
		else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing TabParametros model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->dte_exclusao = 'NOW()';
		
		if ($model->save()) {
			$this->session->setFlashProjeto('success', 'delete');
		}
		else {
			$this->session->setFlashProjeto('danger', 'delete');
		}

		return $this->redirect(['index']);
	}

	/**
	 * Finds the TabParametros model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return TabParametros the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = TabParametros::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
