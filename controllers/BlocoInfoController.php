<?php

namespace app\controllers;

use Yii;
use app\models\TabBlocoInfo;
use app\models\TabBlocoInfoSearch;
use projeto\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\TabForm;

/**
 * BlocoInfoController implements the CRUD actions for TabBlocoInfo model.
 */
class BlocoInfoController extends Controller
{
	protected $viewPath  = '@app/views/bloco-info';
	
	public function getViewPath()
	{
		return Yii::getAlias($this->viewPath);
	}

	/**
	 * Lists all TabBlocoInfo models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		if (!isset(Yii::$app->request->queryParams['TabBlocoInfoSearch']['fk_form'])) {
			throw new NotFoundHttpException("Faltando parâmetro form_fk");
		}

		$searchModel = new TabBlocoInfoSearch();
		$searchModel->servico_fk = $this->getModulo();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$form = TabForm::find()
			->where(['cod_form' => Yii::$app->request->queryParams['TabBlocoInfoSearch']['fk_form']])
			->asArray()
			->one();
		$this->titulo = 'Gerenciar blocos de informações';
		$this->subTitulo = 'Formulário: ' . $form['dsc_form'];
		
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single TabBlocoInfo model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$this->titulo = 'Detalhar bloco de informações';
		$this->subTitulo = '';
			
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}
	
	/**
	 * Creates a new TabBlocoInfo model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		if (!isset(Yii::$app->request->queryParams['TabBlocoInfoSearch']['fk_form'])) {
			throw new NotFoundHttpException("Faltando parâmetro form_fk");
		}
		
		$model = new TabBlocoInfo();
		$model->servico_fk = $this->getModulo();
		$model->fk_form = Yii::$app->request->queryParams['TabBlocoInfoSearch']['fk_form'];

		$this->titulo = 'Incluir bloco de informações';
		$this->subTitulo = 'Formulário: ' . \app\models\TabForm::find($model->fk_form)->asArray()->one()['dsc_form'];
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$this->session->setFlashProjeto( 'success', 'update' );
			return $this->redirect(['view', 'id' => $model->cod_bloco_info]);
		}
		else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing TabBlocoInfo model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
	
		$this->titulo = 'Alterar BlocoInfo';
		$this->subTitulo = '';
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$this->session->setFlashProjeto( 'success', 'update' );
			return $this->redirect(['view', 'id' => $model->cod_bloco_info]);
		}
		else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing TabBlocoInfo model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->dte_exclusao = 'NOW()';
		
		if ($model->save()) {
			$this->session->setFlashProjeto( 'success', 'delete' );
		}
		else {
			$this->session->setFlashProjeto( 'danger', 'delete' );
		}

		return $this->redirect(["index?TabBlocoInfoSearch[fk_form]={$model->fk_form}"]);
	}

	/**
	 * Finds the TabBlocoInfo model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return TabBlocoInfo the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = TabBlocoInfo::findOne($id)) !== null) {
			return $model;
		}
		else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}