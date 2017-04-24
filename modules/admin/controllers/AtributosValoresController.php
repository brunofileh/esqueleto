<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\TabAtributosValores;
use app\models\TabAtributosValoresSearch;
use projeto\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

/**
 * AtributosValoresController implements the CRUD actions for TabAtributosValores model.
 */
class AtributosValoresController extends Controller
{
	public $js = ['@web/js/app/atributos-valores.js'];
	
	protected function breadcrumbs($lastOne)
	{
		$infoModulo = $this->module->info;
		$this->breadcrumbs[] = ['label' => $infoModulo['txt_nome'] , 'url' => Url::toRoute($infoModulo['txt_url'])];
		$this->breadcrumbs[] = ['label' => 'Tab. atributos / valores' , 'url' => Url::toRoute(['/admin/atributos'])];
		$this->breadcrumbs[] = ['label' =>  $lastOne];
	}
	/**
	 * Lists all TabAtributosValores models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new TabAtributosValoresSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		$this->titulo = 'Gerenciar itens';
		$this->subTitulo = '';

		$this->breadcrumbs($this->titulo);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single TabAtributosValores model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$this->titulo = 'Detalhar item';
		$this->subTitulo = '';
		
		$this->breadcrumbs($this->titulo);

		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new TabAtributosValores model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new TabAtributosValores();

		$this->titulo = 'Incluir item';
		$this->subTitulo = '';
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$this->session->setFlashProjeto('success', 'update');
			return $this->redirect([
				'index', 
				'id' => $model->cod_atributos_valores,
				'TabAtributosValoresSearch[fk_atributos_valores_atributos_id]' => $model->fk_atributos_valores_atributos_id,
			]);
		}
		else {
			$this->breadcrumbs($this->titulo);
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing TabAtributosValores model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
	
		$this->titulo = 'Alterar item';
		$this->subTitulo = '';
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$this->session->setFlashProjeto( 'success', 'update' );
			return $this->redirect([
				'index', 
				'id' => $model->cod_atributos_valores,
				'TabAtributosValoresSearch[fk_atributos_valores_atributos_id]' => $model->fk_atributos_valores_atributos_id,
			]);
		}
		else {
			$this->breadcrumbs($this->titulo);
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing TabAtributosValores model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		
		if ($model->delete()) {
			$this->session->setFlashProjeto('success', 'delete');
		}
		else {
			$this->session->setFlashProjeto('danger', 'delete');
		}

		return $this->redirect([
			'index', 
			'TabAtributosValoresSearch[fk_atributos_valores_atributos_id]' => $model->fk_atributos_valores_atributos_id
		]);
	}

	/**
	 * Finds the TabAtributosValores model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return TabAtributosValores the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = TabAtributosValores::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
