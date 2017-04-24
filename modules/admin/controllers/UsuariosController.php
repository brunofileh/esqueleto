<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\TabUsuarios;
use app\modules\admin\models\TabUsuariosSearch;
use app\modules\admin\models\VisUsuariosPerfis;
use projeto\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * UsuariosController implements the CRUD actions for TabUsuarios model.
 */
class UsuariosController extends Controller {

	/**
	 * Lists all TabUsuarios models.
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new \app\modules\admin\models\VisUsuariosPerfisSearch();
		//$parametros = Yii::$app->request->queryParams;
		//setar o modulo de drenagem como default na busca
		$modulo = \app\modules\admin\models\TabModulosSearch::findOne(['id' => 'admin']);

		$searchModel->cod_modulo_fk = $modulo->cod_modulo;
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);


		$this->titulo = 'Gerenciar Usuários';

		return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single TabUsuarios model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id) {
		//usuario nao colocar o id de outra pessoa
		//print_r($this->module->info); exit;

		$this->titulo = 'Detalhar Usuário';
		$model = $this->findModel($id);

		$modulos = VisUsuariosPerfis::findAllAsArray(['cod_usuario_fk' => $id]);

		if ($modulos) {
			foreach ($modulos as $modulo) {
				$mod[] = $modulo['nome_modulo'];
			}
			sort($mod);

			$model->lista_modulos = implode(', ', $mod);
		}

		return $this->render('view', [
				'model' => $model,
		]);
	}

	/**
	 * Creates e Updates a new TabUsuarios  model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionAdmin($id = null) {
		if ($id) {
			$model = $this->findModel($id);
			$acao = 'update';
			$this->titulo = 'Alterar Usuário';
			$this->subTitulo = '';
			$perfis = [];
			$listaPerfil = [];
			$listaModulo = \app\modules\admin\models\TabModulosSearch::find()->where("dte_exclusao is null")->all();
			
			foreach ($model->rlcUsuariosPerfis as $key => $value) {
				$listaPerfil[$value->cod_perfil_fk] = ['cod_perfil' => $value->cod_perfil_fk,
												 'cod_modulo' => $value->tabPerfis->cod_modulo_fk,
												 'txt_perfil' => $value->tabPerfis->txt_nome,
												 'txt_modulo' => $value->tabPerfis->tabModulos->txt_nome];
					
			}
			
			
		} else {
			$acao = 'create';
			$model = new TabUsuariosSearch();
			$this->titulo = 'Incluir Usuário';
			$this->subTitulo = '';
			$listaPerfil = [];
			$listaModulo = \app\modules\admin\models\TabModulosSearch::find()->where("dte_exclusao is null")->all();
		}
		
		
		$model->scenario = TabUsuariosSearch::SCENARIO_ADMIN;

		if (Yii::$app->request->isPost) {
			
			$model->load(Yii::$app->request->post());
			
			$transaction = $this->db->beginTransaction();
			try {

				$model->txt_senha = substr(md5(date('YmdHis')), 0, 6);

				$txtSenha = $model->txt_senha;
				$isNewRecord = $model->isNewRecord;
				$post = Yii::$app->request->post();

				//recupera os dados de altura largura x e y
				$model->txt_imagem_crop = $post['txt_imagem_cropping-cropping'];

				//recupera os dados da imagem
				$img = UploadedFile::getInstance($model, 'txt_imagem_cropping');

				if ($model->validate()) {

					if ($img) {

						//caso a pasta do modulo nao exista, eh criado
						if (!file_exists(Yii::getAlias("@webroot/img/usuarios"))) {
							mkdir(Yii::getAlias("@webroot/img/usuarios"));
						}

						//salva imagem na pasta
						$img = Image::crop(
								$img->tempName, $model->txt_imagem_crop['width'], $model->txt_imagem_crop['height'], [
								$model->txt_imagem_crop['x'],
								$model->txt_imagem_crop['y']
								]
							)->save(Yii::getAlias("@webroot/img/usuarios/{$model->cod_usuario}.jpg"));
						$model->txt_imagem = "@web/img/usuarios/{$model->cod_usuario}.jpg";
					}

					$perfis = \yii::$app->session->get('perfil-modulo');

					if ($model->save()) {

						$perfis = \yii::$app->session->get('perfil-modulo');
						if( ! $model->isNewRecord){
							\app\modules\admin\models\RlcUsuariosPerfisSearch::deleteAll(['cod_usuario_fk'=>$model->cod_usuario]);
						}
						if ($perfis) {
							foreach ($perfis as $key => $value) {
								$perfil = new \app\modules\admin\models\RlcUsuariosPerfisSearch();
								$perfil->cod_perfil_fk = $value['cod_perfil'];
								$perfil->cod_usuario_fk = $model->cod_usuario;
								$perfil->save();
							}
						}
						// garantir q salvou antes de enviar o email
						//$this->app->mailer->registroNovoUsuario($model, $txtSenha);
					}
					$transaction->commit();
					\yii::$app->session->set('perfil-modulo', []);
					$this->session->setFlashProjeto('success', $acao);
					return $this->redirect(['view', 'id' => $model->cod_usuario]);
				}
			
			} catch (Exception $e) {
				$transaction->rollBack();
				throw $e;
			}
		} else {
			
				$listaPerfil = (!$listaPerfil) ? [] : $listaPerfil;

				if( Yii::$app->request->headers['accept']!='*/*' ){
					\yii::$app->session->set('perfil-modulo', $listaPerfil);
				}
			
		} 

		return $this->render('admin', [
				'model' => $model,
				'listaPerfil' => $listaPerfil,
				'listaModulo' => $listaModulo,
		]);
	}

	/**
	 * Creates a new TabUsuarios model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new TabUsuarios();

		$this->titulo = 'Incluir Usuario';
		$this->subTitulo = '';

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$this->session->setFlashProjeto('success', 'update');
			return $this->redirect(['view', 'id' => $model->cod_usuario]);
		} else {
			return $this->render('create', [
					'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing TabUsuarios model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);

		$this->titulo = 'Alterar Usuario';
		$this->subTitulo = '';

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$this->session->setFlashProjeto('success', 'update');

			return $this->redirect(['view', 'id' => $model->cod_usuario]);
		} else {
			return $this->render('update', [
					'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing TabUsuarios model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id) {
		$model = $this->findModel($id);
		$model->dte_exclusao = 'NOW()';
		$model->txt_ativo = '0';

		if ($model->save()) {
			$this->session->setFlashProjeto('success', 'delete');
		} else {
			$this->session->setFlashProjeto('danger', 'delete');
		}

		return $this->redirect(['index']);
	}

	/**
	 * Finds the TabUsuarios model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return TabUsuarios the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = TabUsuariosSearch::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	
	public function actionVerificaCpf() {
		$cpf = Yii::$app->request->post()['dados'];

		$usuario = TabUsuariosSearch::find()->where(['num_cpf' => $cpf])->asArray()->one();
		
		echo count($usuario);
	}

}
