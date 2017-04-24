<?php

namespace app\controllers;

use Yii;
use app\models\TabMunicipios;
use app\models\TabMunicipiosSearch;
use projeto\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * MunicipiosController implements the CRUD actions for TabMunicipios model.
 */
class MunicipiosController extends Controller
{

    /**
     * Lists all TabMunicipios models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TabMunicipiosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		$this->titulo = 'Gerenciar Municipios';
		$this->subTitulo = '';
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TabMunicipios model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
		$this->titulo = 'Detalhar Municipio';
		$this->subTitulo = '';
			
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

	
	
	/**
	 * Creates e Updates a new TabMunicipios  model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionAdmin( $id = null )
	{

		if ($id)
		{
		
			$model = $this->findModel($id);
			$acao = 'update';
			$this->titulo = 'Alterar Municipio';
			$this->subTitulo = '';
		}
		else
		{
		
			$acao = 'create';
			$model = new TabMunicipios();
			$this->titulo = 'Incluir Municipio';
			$this->subTitulo = '';
		}

		if ($model->load( Yii::$app->request->post() ) && $model->save())
		{

			$this->session->setFlashProjeto( 'success', $acao );
			return $this->redirect( ['view', 'id' => $model->cod_municipio ]);
		}

		return $this->render( 'admin', [
				'model' => $model,
			] );
	}
	
	
	
    /**
     * Creates a new TabMunicipios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TabMunicipios();

		$this->titulo = 'Incluir Municipio';
		$this->subTitulo = '';
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			
			$this->session->setFlashProjeto( 'success', 'update' );
			
            return $this->redirect(['view', 'id' => $model->cod_municipio]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TabMunicipios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
	
		$this->titulo = 'Alterar Municipio';
		$this->subTitulo = '';
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
		
			$this->session->setFlashProjeto( 'success', 'update' );
            
			return $this->redirect(['view', 'id' => $model->cod_municipio]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TabMunicipios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		
		$model = $this->findModel($id);
		$model->dte_exclusao = 'NOW()';
		
		if ($model->save())
		{
			
			$this->session->setFlashProjeto( 'success', 'delete' );
		}
		else
		{
			
			$this->session->setFlashProjeto( 'danger', 'delete' );
		}

        return $this->redirect(['index']);
    }

    /**
     * Finds the TabMunicipios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TabMunicipios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TabMunicipios::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	
    /**
     * ListaUf todos os municipios de uma determinada uf
     * @param string $uf
     */
    public function actionLista($uf)
    {
		
       $municipios = TabMunicipiosSearch::find()->where( ['sgl_estado_fk'=> $uf])->asArray()->orderBy( 'txt_nome' )->all();
	 
	   if($municipios){
		   
		    echo "<option value=''>Selecione o Município</option>";
			
            foreach($municipios as $key => $municipio){
                echo "<option value='".$municipio['cod_municipio']."'>".$municipio['txt_nome']."</option>";
            }
        }
        else{
            echo "<option></option>";
        }
		
    }
}
