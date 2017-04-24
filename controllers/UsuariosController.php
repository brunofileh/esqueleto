<?php

namespace app\controllers;

use Yii;
use app\modules\admin\models\TabUsuarios;
use app\modules\admin\models\TabUsuariosSearch;
use app\modules\admin\models\VisUsuariosPerfis;
use projeto\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\UploadedFile;
use \yii\imagine\Image;

/**
 * UsuariosController implements the CRUD actions for TabUsuarios model.
 */
class UsuariosController extends Controller
{

	public function behaviors()
	{
		return [
			'verbs' => [
				'class'		 => VerbFilter::className() ,
				'actions'	 => [
					'delete' => ['post'] ,
				] ,
			]
		];

	}

	/**
	 * Displays a single TabUsuarios model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView()
	{
		if (((strpos(Yii::$app->request->referrer, 'usuarios/admin')) === false) && ((strpos(Yii::$app->request->referrer, 'usuarios/view')) === false))
			$this->session->set('referrer', Yii::$app->request->referrer);
		
		//usuario nao colocar o id de outra pessoa

		$this->titulo	 = 'Visualizar';
		$model			 = $this->findModel( $this->user->id );

		$modulos = VisUsuariosPerfis::findAllAsArray( ['cod_usuario_fk' => $this->user->id] );

		if ($modulos){

			foreach ($modulos as $modulo){

				$mod[] = $modulo['nome_modulo'];
			}
			sort( $mod );

			$model->lista_modulos = implode( ', ' , $mod );
		}

		return $this->render( 'view' , [
				'model' => $model ,
			] );

	}

	/**
	 * Creates e Updates a new TabUsuarios  model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionAdmin()
	{

		$model			 = $this->findModel( $this->user->id );
		$acao			 = 'update';
		$this->titulo	 = 'Editar';
		$this->subTitulo = 'Alterar';

		$model->scenario = TabUsuariosSearch::SCENARIO_ADMIN;

		if ($model->load( Yii::$app->request->post() )){

			$post = Yii::$app->request->post();

			//recupera os dados de altura largura x e y
			$model->txt_imagem_crop = $post['txt_imagem_cropping-cropping'];

			//recupera os dados da imagem
			$img = UploadedFile::getInstance( $model , 'txt_imagem_cropping' );

			if ($model->validate()){

				if ($img){

					//caso a pasta do modulo nao exista, eh criado
					if (!file_exists( Yii::getAlias( "@webroot/img/usuarios" ) )){

						mkdir( Yii::getAlias( "@webroot/img/usuarios" ) );
					}

					//salva imagem na pasta
					$img = Image::crop( $img->tempName , $model->txt_imagem_crop['width'] , $model->txt_imagem_crop['height'] , [ $model->txt_imagem_crop['x'] ,
							$model->txt_imagem_crop['y']] )
						->save( Yii::getAlias( "@webroot/img/usuarios/{$model->cod_usuario}.jpg" ) );

					$model->txt_imagem = "@web/img/usuarios/{$model->cod_usuario}.jpg";
				}

				$model->save();

				$this->session->setFlashProjeto( 'success' , $acao );
				return $this->redirect( ['view'] );
			}
		}

		return $this->render( 'admin' , [
				'model' => $model ,
			] );

	}

	/**
	 * Finds the TabUsuarios model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return TabUsuarios the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel( $id )
	{
		if (($model = TabUsuariosSearch::findOne( $id )) !== null){
			return $model;
		} else{
			throw new NotFoundHttpException( 'The requested page does not exist.' );
		}

	}

}