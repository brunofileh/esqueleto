<?php

namespace app\controllers;

use Yii;
use app\models\RlcModulosPrestadores;
use app\models\RlcModulosPrestadoresSearch;
use projeto\web\Controller;
use yii\web\NotFoundHttpException;
use \app\models\TabPrestadoresSearch;
use \app\modules\admin\models\TabModulosSearch;

/**
 * ModulosPrestadoresController implements the CRUD actions for RlcModulosPrestadores model.
 */
class ModulosPrestadoresController extends Controller
{

	protected $viewPath = '@app/views/modulos-prestadores';

	public function getViewPath()
	{
		return Yii::getAlias($this->viewPath);

	}

	/**
	 * Lists all RlcModulosPrestadores models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel	 = new RlcModulosPrestadoresSearch();
		$dataProvider	 = $searchModel->search(Yii::$app->request->queryParams);

		$this->titulo	 = 'Gerenciar ModulosPrestadores';
		$this->subTitulo = '';

		return $this->render('index', [
				'searchModel'	 => $searchModel,
				'dataProvider'	 => $dataProvider,
		]);

	}

	/**
	 * Displays a single RlcModulosPrestadores model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($cod_prestador)
	{
		$this->titulo	 = 'Detalhar ModulosPrestadore';
		$this->subTitulo = '';

		$model = TabPrestadoresSearch::findOne($cod_prestador);

		return $this->render('view', [
				'model' => $model,
		]);

	}

	
	/**
	 * Creates e Updates a new RlcModulosPrestadores  model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionAdmin($cod_prestador = null)
	{

		if ($cod_prestador) {

			$model			 = TabPrestadoresSearch::findOne($cod_prestador);
			$acao			 = 'update';
			$this->titulo	 = 'Alterar Dados';
			$this->subTitulo = $model->txt_nome;

			$this->breadcrumbs[] = ['label' => 'Dados da prefeitura', 'url' => \yii\helpers\Url::toRoute(['prefeituras/view', 'id' => $model->cod_prestador])];
			$this->breadcrumbs[] = ['label' => $this->titulo];

			//popula a lista de monicipio por módulo
			if ($model->rlcModulosPrestadores) {

				foreach ($model->rlcModulosPrestadores as $value) {
					$value->setListaMunicipios();
				}
			}

			if ( ! $model->bln_atualizacao) {
				$this->titulo		 = 'Atualizar dados do órgão responsável';
				$this->homeLink		 = ['label' => 'Dados da prefeitura', 'url' => \yii\helpers\Url::toRoute(['prefeituras/admin', 'id' => $model->cod_prestador])];
				$this->breadcrumbs[] = ['label' => $this->titulo];
			}
		} else {

			$acao			 = 'create';
			$model			 = new RlcModulosPrestadoresSearch();
			$this->titulo	 = 'Lista de Contato por Módulo';
			$this->subTitulo = '';
		}

		if (Yii::$app->request->post()) {

			$post = Yii::$app->request->post();

			$transaction = $this->db->beginTransaction();
			try {
				
				$erros = false;
				//atualiza a model por modulos e salva
				foreach ($post['RlcModulosPrestadoresSearch'] as $key => $value) {

					$model->rlcModulosPrestadores[$key]->load($value);


					if (!$model->rlcModulosPrestadores[$key]->save()) {
						//Verifica qual aba ocorreu o erro
						$erros[] = 'Aba ' . $model->rlcModulosPrestadores[$key]->tabModulos->txt_nome;
					}

					//para carregar o municipio caso de erro de validação
					//$model->rlcModulosPrestadores[$key]->listaMunicipios  = 11;

					$model->rlcModulosPrestadores[$key]->setListaMunicipios();
				}

				if (!$erros) {
					
					$urlControler = ($this->module->id == 'gestao') ? 'prefeituras/view' : 'prestadores/view';
					
					if( ! $model->bln_atualizacao){
						$model->bln_atualizacao = true;
						$model->save();	
						$urlControler = '/drenagem/inicio';
					}
					
					
					$transaction->commit();
					$this->session->setFlashProjeto('success', $acao);

					

					$mod_prestador = RlcModulosPrestadoresSearch::findOneAsArray([
							'cod_prestador_fk'	 => $model->cod_prestador,
							'cod_modulo_fk'		 => TabModulosSearch::find()->where(['id' => 'drenagem'])->one()->cod_modulo
					]);


					return $this->redirect(["{$urlControler}", 'id' => $model->cod_prestador]);
				} else {

					$this->session->setFlash('danger', 'Erro(s): ' . implode(', ', $erros));
				}
			} catch (Exception $ex) {
				$this->session->setFlash('danger', $ex->getMessage());
				$transaction->rollBack();
			}
		}

		return $this->render('admin', [
				'model' => $model,
		]);

	}

	/**
	 * Deletes an existing RlcModulosPrestadores model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{

		$model				 = $this->findModel($id);
		$model->dte_exclusao = 'NOW()';

		if ($model->save()) {

			$this->session->setFlashProjeto('success', 'delete');
		} else {

			$this->session->setFlashProjeto('danger', 'delete');
		}

		return $this->redirect(['index']);

	}

	/**
	 * Finds the RlcModulosPrestadores model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return RlcModulosPrestadores the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = RlcModulosPrestadores::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}

	}


}
