<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\TabAtributos;
use app\models\TabAtributosValores;
use app\models\TabAtributosSearch;
use projeto\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * AtributosController implements the CRUD actions for TabAtributos model.
 */
class AtributosController extends Controller
{
	public $js = ['@web/js/app/atributos-valores.js'];

	/**
	 * Lists all TabAtributos models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new TabAtributosSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->pagination->pageSize = 10;

		$this->titulo = 'Gerenciar Atributos';
		$this->subTitulo = '';
		
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single TabAtributos model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$this->titulo = 'Detalhar Atributo';
		$this->subTitulo = '';
			
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}
	
	/**
	 * Creates a new TabAtributos model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new TabAtributos();

		$this->titulo = 'Incluir Atributo';
		$this->subTitulo = '';
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$this->session->setFlash('success', 'Opção cadastrada com sucesso! Agora cadastre os itens.');
			return $this->redirect([
				"{$this->module->info['txt_url']}/atributos-valores", 
				'TabAtributosValoresSearch[fk_atributos_valores_atributos_id]' => $model->cod_atributos
			]);
		}
		else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing TabAtributos model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
	
		$this->titulo = 'Alterar Atributo';
		$this->subTitulo = '';
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$this->session->setFlashProjeto('success', 'update');
			return $this->redirect(['index']);
		}
		else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing TabAtributos model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);

		/**
		 * Verifica se tem itens relacionados na tab_atributos_valores
		 */
		$temAssoc = TabAtributosValores::find()->where([
			'fk_atributos_valores_atributos_id' => $model->cod_atributos
		])->count() > 0;
		
		if ($temAssoc) {
			$this->session->setFlashProjeto('danger', 'delete', 'Primeiro apague os itens em atributos valores.');
			return $this->redirect(['index']);
		}

		if ($model->delete()) {
			$this->session->setFlashProjeto('success', 'delete');
		}
		else {
			$this->session->setFlashProjeto('danger', 'delete');
		}

		return $this->redirect(['index']);
	}

	/**
	 * Finds the TabAtributos model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return TabAtributos the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = TabAtributos::findOne($id)) !== null) {
			return $model;
		}
		else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
