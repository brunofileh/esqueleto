<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\RlcUsuariosPerfis;
use app\modules\admin\models\RlcUsuariosPerfisSearch;
use app\modules\admin\models\VisUsuariosPerfisSearch;
use yii\helpers\Json;
use projeto\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\Exception;
use yii\helpers\Url;

/**
 * UsuariosPerfisController implements the CRUD actions for RlcUsuariosPerfis model.
 */
class UsuariosPerfisController extends Controller
{

	public $activeMenu = 'Módulo';
	
	/**
	 * Creates a new RlcUsuariosPerfis model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionAdmin( $cod_perfil )
	{

		$model					 = new RlcUsuariosPerfisSearch();
		$model->cod_perfil_fk	 = $cod_perfil;

		$this->subTitulo = "Módulo: " . $model->tabPerfis->tabModulos->txt_nome;
		$this->titulo	 = 'Vincular Usuário ao Perfil';

		//busca usuarios vinculados com esse perfil
		$usuariosPerfil		 = RlcUsuariosPerfisSearch::findAllAsArray( ["cod_perfil_fk" => $cod_perfil] );
		$arrayUsuariosPerfil = [];
		
		//coloca os usuarios em array par ao componete multiselect poder ler
		if ($usuariosPerfil){

			foreach ($usuariosPerfil as $usuarioPerfil){

				$arrayUsuariosPerfil[] = $usuarioPerfil['cod_usuario_fk'];
			}
		}

		//busca todos os usuarios que:
		// - não estao vinculado a perfil nenhum.
		// - nao estaja vincula a nenhum perfil do modulo solicitado
		// - e os que estao vinculados a perfil solicitado (por causa do componete multselect)
		
		$listaUsuarios = VisUsuariosPerfisSearch::getVinculoUsuariosPerfil($model->tabPerfis->cod_modulo_fk, $cod_perfil); 									 
													
		if ($model->load( Yii::$app->request->post() )){

			$transaction = $this->db->beginTransaction();

			try{

				if ($model->lista_usuarios){

					$lista = (Json::decode( $model->lista_usuarios )) ? Json::decode( $model->lista_usuarios ) : [];


					if (array_diff_assoc( $arrayUsuariosPerfil , $lista ) || array_diff_assoc( $lista , $arrayUsuariosPerfil )){

						RlcUsuariosPerfisSearch::deleteAll( [ 'cod_perfil_fk' => $model->cod_perfil_fk] );

						foreach ($lista as $usuario){

							$usuariosPerfil					 = new RlcUsuariosPerfisSearch();
							$usuariosPerfil->cod_usuario_fk	 = $usuario;
							$usuariosPerfil->cod_perfil_fk	 = $model->cod_perfil_fk;

							if (!$usuariosPerfil->save()){

								throw new Exception( 'Erro ao salvar' );
							}
						}
					}
				}

				$this->session->setFlash( 'success' , 'Vinculação efetivada com sucesso.' );
				$transaction->commit();

				return $this->redirect( Url::to( ['perfis/index' , 'cod_modulo' => $model->tabPerfis->cod_modulo_fk] ) );
			}
			catch (Exception $e){

				$transaction->rollBack();
				$this->session->setFlash( 'danger' , $e->getMessage() );
			}
		}

		$model->lista_usuarios = Json::encode( $arrayUsuariosPerfil );
		
		$this->breadcrumbs[] = ['label' => 'Controle de Acesso' , 'url' => Url::toRoute( '/admin' )];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Módulos' , 'url' => Url::toRoute( ['/admin/modulos', 'cod_modulo' => $model->tabPerfis->cod_modulo_fk] )];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Perfis' , 'url' => Url::toRoute( ['/admin/perfis', 'cod_modulo' => $model->tabPerfis->cod_modulo_fk] )];
		$this->breadcrumbs[] = ['label' =>  $this->titulo];
	
		return $this->render( 'admin' , [
				'model'			 => $model ,
				'listaUsuarios'	 => $listaUsuarios
			] );
	}

	/**
	 * Updates an existing RlcUsuariosPerfis model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate( $id )
	{
		$model = $this->findModel( $id );

		if ($model->load( Yii::$app->request->post() ) && $model->save()){
			return $this->redirect( ['view' , 'id' => $model->cod_usuario_perfil] );
		} else{
			return $this->render( 'update' , [
					'model' => $model ,
				] );
		}

	}

	/**
	 * Deletes an existing RlcUsuariosPerfis model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete( $id )
	{
		$model				 = $this->findModel( $id );
		$model->dte_exclusao = 'NOW()';
		$model->save();

		return $this->redirect( ['index'] );
	}

	/**
	 * Finds the RlcUsuariosPerfis model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return RlcUsuariosPerfis the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel( $id )
	{
		if (($model = RlcUsuariosPerfisSearch::findOne( $id )) !== null){
			return $model;
		} else{
			throw new NotFoundHttpException( 'The requested page does not exist.' );
		}

	}

}