<?php

namespace app\controllers;

use Yii;
use app\models\TabModeloDocs;
use app\models\TabModeloDocsSearch;
use projeto\web\Controller;
use yii\web\NotFoundHttpException;
use kartik\mpdf\Pdf;
use projeto\Util;

/**
 * ModeloDocsController implements the CRUD actions for TabModeloDocs model.
 */
class ModeloDocsController extends Controller
{
	public $js = ['@web/js/app/modelo-docs.js'];
	protected $viewPath = '@app/views/modelo-docs';
	
	public function getViewPath()
	{
		return Yii::getAlias($this->viewPath);
	}
	
	public function actionIndex()
	{
		$searchParams = Yii::$app->request->queryParams;
		$searchParams['TabModeloDocsSearch']['modulo_fk'] = $this->module->info['cod_modulo'];
		
		$searchModel = new TabModeloDocsSearch();
		$dataProvider = $searchModel->search($searchParams);

		$this->titulo = 'Gerenciar modelos de documentos';
		$this->subTitulo = '';
		
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionView($id)
	{
		$this->titulo = 'Detalhar modelo de documento';
		$this->subTitulo = '';
			
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	public function actionCreate()
	{
		$model = new TabModeloDocs();

		$this->titulo = 'Incluir modelo de documento';
		$this->subTitulo = '';
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$this->session->setFlashProjeto( 'success', 'update' );
			return $this->redirect(['view', 'id' => $model->cod_modelo_doc]);
		}
		else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
	
		$this->titulo = 'Alterar modelo de documento';
		$this->subTitulo = '';
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$this->session->setFlashProjeto( 'success', 'update' );
			return $this->redirect(['update', 'id' => $model->cod_modelo_doc]);
		}
		else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

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

		return $this->redirect(['index']);
	}

	protected function findModel($id)
	{
		if (($model = TabModeloDocs::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('A página solicitada não existe.');
		}
	}
	
	public function actionPrint()
	{
		if (null === ($id = $this->request->get('id'))) {
			throw new NotFoundHttpException('A página solicitada não existe.');
		}
		
		$model = $this->findModel($id);
		if (Util::attrVal($model->tipo_modelo_documento_fk) == 'tipo-modelo-documento-email') {
			return \projeto\pdf\ModeloDoc::gerarEmail($model->sgl_id);
		}
		else {
			$date = date('d-m-Y-H-i');
			return \projeto\pdf\ModeloDoc::gerarPdf($model->sgl_id, [
				'destination' => Pdf::DEST_BROWSER,
				'filename' => Yii::getAlias("@runtime/tmp/{$this->module->id}-{$model->sgl_id}-{$date}.pdf"),
			]);
		}
	}
}
