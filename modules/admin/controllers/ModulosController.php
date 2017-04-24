<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\TabModulos;
use app\modules\admin\models\TabModulosSearch;
use projeto\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * ModulosController implements the CRUD actions for TabModulos model.
 */
class ModulosController extends Controller
{

	/**
	 * Lists all TabModulos models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel	 = new TabModulosSearch();
		$dataProvider	 = $searchModel->search( Yii::$app->request->queryParams );

		$this->titulo = 'Gerenciar Módulos';
			
		return $this->render( 'index' , [
				'searchModel'	 => $searchModel ,
				'dataProvider'	 => $dataProvider ,
			] );

	}

	/**
	 * Displays a single TabModulos model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView( $id )
	{
		$this->titulo = 'Detalhar Módulo';

		$model = $this->findModel( $id );

		if ($model->tabPerfis){

			foreach ($model->tabPerfis as $perfil){
				$pefis[] = $perfil->txt_nome;
			}
			sort( $pefis );

			$model->lista_perfis = implode( ', ' , $pefis );
		}

		return $this->render( 'view' , [
				'model' => $model ,
			] );

	}

	/**
	 * Creates e Updates a new TabModulos  model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionAdmin( $id = null )
	{

		if ($id){

			$model			 = $this->findModel( $id );
			$acao			 = 'update';
			$this->titulo	 = 'Alterar Módulo';
		} else{

			$acao			 = 'create';
			$model			 = new TabModulosSearch();
			$this->titulo	 = 'Incluir Módulo';
		}
        
        $model->scenario = 'admin';

		if ($model->load( Yii::$app->request->post())){
			
			$post = Yii::$app->request->post();
			
			//recupera os dados de altura largura x e y
			$model->txt_icone_crop = $post['txt_icone_cropping-cropping'];
			
			//recupera os dados da imagem
			$img = UploadedFile::getInstance($model, 'txt_icone_cropping');
			
			if($img){
				//caso a pasta do modulo nao exista, eh criado
				if( ! file_exists( Yii::getAlias("@webroot/img/modulos/{$model->id}"))){

					mkdir( Yii::getAlias("@webroot/img/modulos/{$model->id}"));			
				}
				
				//salva imagem na pasta
				$img = Image::crop($img->tempName ,	$model->txt_icone_crop['width'] , 
													$model->txt_icone_crop['height'], 
													[ $model->txt_icone_crop['x'], 
													  $model->txt_icone_crop['y'] ])
								->save( Yii::getAlias("@webroot/img/modulos/{$model->id}/icone-modulo.png") );

				$model->txt_icone = "@web/img/modulos/{$model->id}/icone-modulo.png";
			}
			
			if ($model->save()){			
				$this->session->setFlashProjeto( 'success' , $acao );

				return $this->redirect( ['view' , 'id' => $model->cod_modulo] );
			}
		}

		return $this->render( 'admin' , [
				'model' => $model ,
			] );

	}

	
	/**
	 * Deletes an existing TabModulos model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete( $id )
	{

		$model = $this->findModel( $id );
		$msg = [];
		
		if ($model->tabPerfis){

			$msg[] = 'perfil';
		}

		if ($msg){

			$msg = 'Erro: Existe ' . implode( ", " , $msg ) . ' vinculado ao módulo.';

			$this->session->setFlash( 'danger' , $msg );
		} else{

			if ($model->delete()){

				$this->session->setFlashProjeto( 'success' , 'delete' );
			} else{

				$this->session->setFlashProjeto( 'danger' , 'delete' );
			}
		}

		return $this->redirect( ['index'] );
	}

	/**
	 * Finds the TabModulos model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return TabModulos the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel( $id )
	{
		if (($model = TabModulosSearch::findOne( $id )) !== null){
			return $model;
		} else{
			throw new NotFoundHttpException( 'The requested page does not exist.' );
		}

	}
	
	/**
     * Lista todos os prestadores de uma determinado municipio
     * @param string $codPrestador
     */
    public function actionLista($codPrestador)
    {
		
       $modulos = TabModulosSearch::find()->joinWith('rlcModulosPrestadores')->where("cod_prestador_fk='{$codPrestador}'")->asArray()->orderBy( 'txt_nome' )->all();
		   
		if($modulos){
			
            foreach($modulos as $key => $modulo){
                echo "<option value='".$modulo['cod_modulo']."'>".$modulo['txt_nome']."</option>";
            }
        }
        else{
            echo "<option></option>";
        }
		
		  
		
    }

}