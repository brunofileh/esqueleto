<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\RlcMenusPerfis;
use app\modules\admin\models\RlcMenusPerfisSearch;
use projeto\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use yii\helpers\Url;
use app\modules\admin\models\VisMenusPerfisSearch;

/**
 * MenusPerfisController implements the CRUD actions for RlcMenusPerfis model.
 */
class MenusPerfisController extends Controller
{

	public $activeMenu = 'Módulo';
	
	/**
	 * Creates e Updates a new RlcMenusPerfis  model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionAdmin( $cod_perfil )
	{

		$model					 = new RlcMenusPerfisSearch();
		$model->cod_perfil_fk	 = $cod_perfil;

		$menusPerfil		 = RlcMenusPerfisSearch::findAllAsArray( ["cod_perfil_fk" => $cod_perfil] );
		$arrayMenusPerfil	 = [];

		if ($menusPerfil){

			foreach ($menusPerfil as $menuPerfil){

				$arrayMenusPerfil[] = $menuPerfil['cod_menu_fk'];
			}
		}


		$listaMenus = VisMenusPerfisSearch::find()->where( "
												( 
													(
													 cod_modulo_fk= {$model->tabPerfis->cod_modulo_fk} 
													)  
												OR cod_perfil_fk IS NULL 
												)
										" )->select( 'distinct(cod_menu_fk) cod_menu_fk, nome_menu' );



		if ($model->load( Yii::$app->request->post() )){

			$transaction = $this->db->beginTransaction();

			try{

				if ($model->lista_menus){

					$lista = (Json::decode( $model->lista_menus )) ? Json::decode( $model->lista_menus ) : [];


					if (array_diff_assoc( $arrayMenusPerfil , $lista ) || array_diff_assoc( $lista , $arrayMenusPerfil )){

						RlcMenusPerfisSearch::deleteAll( [ 'cod_perfil_fk' => $model->cod_perfil_fk] );

						foreach ($lista as $menu){

							$menuPerfil					 = new RlcMenusPerfisSearch();
							$menuPerfil->cod_menu_fk	 = $menu;
							$menuPerfil->cod_perfil_fk	 = $model->cod_perfil_fk;

							if (!$menuPerfil->save()){

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

		$this->subTitulo = "Módulo: " . $model->tabPerfis->tabModulos->txt_nome;

		$this->titulo = 'Vincular Menu ao Perfil';

		$model->lista_menus = Json::encode( $arrayMenusPerfil );

		$this->breadcrumbs[] = ['label' => 'Controle de Acesso' , 'url' => Url::toRoute( '/admin' )];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Módulos' , 'url' => Url::toRoute( ['/admin/modulos', 'cod_modulo' => $model->tabPerfis->cod_modulo_fk] )];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Perfis' , 'url' => Url::toRoute( ['/admin/perfis', 'cod_modulo' => $model->tabPerfis->cod_modulo_fk] )];
		$this->breadcrumbs[] = ['label' =>  $this->titulo ];	
		
		return $this->render( 'admin' , [
				'model'		 => $model ,
				'listaMenus' => $listaMenus
			] );

	}

	/**
	 * Finds the RlcMenusPerfis model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return RlcMenusPerfis the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel( $id )
	{
		if (($model = RlcMenusPerfis::findOne( $id )) !== null){
			return $model;
		} else{
			throw new NotFoundHttpException( 'The requested page does not exist.' );
		}

	}

}