<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\RlcPerfisFuncionalidadesAcoes;
use app\modules\admin\models\RlcPerfisFuncionalidadesAcoesSearch;
use app\modules\admin\models\TabFuncionalidadesSearch;
use app\modules\admin\models\TabRestricoesUsuarios;
use yii\helpers\Url;
use projeto\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\admin\models\TabAcoesSearch;
use app\modules\admin\models\TabPerfisSearch;
use yii\helpers\Json;

/**
 * PerfisFuncionalidadesAcoesController implements the CRUD actions for RlcPerfisFuncionalidadesAcoes model.
 */
class PerfisFuncionalidadesAcoesController extends Controller
{

	/**
	 * Creates e Updates a new RlcPerfisFuncionalidadesAcoes  model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionAdmin($cod_funcionalidade, $cod_modulo)
	{
		$model			 = new RlcPerfisFuncionalidadesAcoesSearch();
		$funcionalidade	 = TabFuncionalidadesSearch::find()->where(['cod_funcionalidade' => $cod_funcionalidade])->one();

		$this->titulo					 = 'Vincular acões a funcionalidade';
		$this->subTitulo				 = 'Funcionalidade: ' . $funcionalidade->dsc_funcionalidade;
		$listaAcoes						 = TabAcoesSearch::find()->Where('dte_exclusao IS NULL')->orderBy('dsc_acao');
		$listaPerfis					 = TabPerfisSearch::perfisPorModulo($cod_modulo);
		$model->cod_funcionalidade_fk	 = $cod_funcionalidade;

		if ($model->load(Yii::$app->request->post())) {

			$arrAcoesPerfisBanco = array ();
			$arrAcoesPerfis		 = RlcPerfisFuncionalidadesAcoesSearch::findAllAsArray(['cod_funcionalidade_fk' => $model->cod_funcionalidade_fk, 'cod_perfil_fk' => $model->cod_perfil_fk]);

			if ($arrAcoesPerfis) {
				foreach ($arrAcoesPerfis as $arr) {
					$restricao				 = TabRestricoesUsuarios::findAllAsArray(['cod_perfil_funcionalidade_acao_fk' => $arr['cod_perfil_funcionalidade_acao']]);
					if (count($restricao) > 0)
						$arrAcoesPerfisBanco[]	 = $arr['cod_acao_fk'] . "-" . $arr['cod_perfil_fk'];
				}
			}

			$arrAcoesPerfisForm = array ();
			if ($model->lista_acao) {
				foreach (Json::decode($model->lista_acao) as $acao) {

					$arrAcoesPerfisForm[] = $acao . "-" . $model->cod_perfil_fk;
				}
			}

			$arrDiff = array_diff($arrAcoesPerfisBanco, $arrAcoesPerfisForm);

			$arrAcoes	 = $arrPerfis	 = array ();

			foreach ($arrDiff as $arr) {
				$sep		 = explode("-", $arr);
				$arrAcoes[]	 = TabAcoesSearch::find()->where(['cod_acao' => $sep[0]])->one()->dsc_acao;
				$arrPerfis[] = TabPerfisSearch::find()->where(['cod_perfil' => $sep[1]])->one()->dsc_perfil;
			}

			$arrAcoes	 = array_unique($arrAcoes);
			$arrPerfis	 = array_unique($arrPerfis);

			if (count($arrAcoes) > 0 && count($arrPerfis) > 0) {
				$this->session->setFlash('danger', 'Erro: Existem Ações (' . implode(", ", $arrAcoes) . ') e Perfis (' . implode(", ", $arrPerfis) . ') com restrições de Usuários e que não podem ser excluídos desta Funcionalidade.');
			} else {

				$transaction = $this->db->beginTransaction();
				try {

					if ($model->lista_acao) {

						$notIn = implode(',', Json::decode($model->lista_acao));

						if ($notIn) {
							RlcPerfisFuncionalidadesAcoesSearch::deleteAll('cod_acao_fk not in (' . $notIn . ') and cod_funcionalidade_fk = ' . $model->cod_funcionalidade_fk . ' and cod_perfil_fk = ' . $model->cod_perfil_fk);
						} else {
							RlcPerfisFuncionalidadesAcoesSearch::deleteAll('cod_funcionalidade_fk = ' . $model->cod_funcionalidade_fk . ' and cod_perfil_fk = ' . $model->cod_perfil_fk);
						}

						foreach (Json::decode($model->lista_acao) as $acao) {

							$modelRlcPerfisFuncionalidadesAcoes = RlcPerfisFuncionalidadesAcoesSearch::find()->where('cod_funcionalidade_fk = ' . $model->cod_funcionalidade_fk . ' and cod_acao_fk = ' . $acao . ' and cod_perfil_fk = ' . $model->cod_perfil_fk)->one();
							if (!$modelRlcPerfisFuncionalidadesAcoes) {
								$modelRlcPerfisFuncionalidadesAcoes							 = new RlcPerfisFuncionalidadesAcoesSearch();
								$modelRlcPerfisFuncionalidadesAcoes->cod_funcionalidade_fk	 = $model->cod_funcionalidade_fk;
								$modelRlcPerfisFuncionalidadesAcoes->cod_acao_fk			 = $acao;
								$modelRlcPerfisFuncionalidadesAcoes->cod_perfil_fk			 = $model->cod_perfil_fk;
								$modelRlcPerfisFuncionalidadesAcoes->save();
							}
						}
					}
					$transaction->commit();
					$this->session->setFlashProjeto('success', 'update');

				} catch (Exception $e) {
					$transaction->rollBack();
					throw $e;
				}
			}
		}

		$infoModulo			 = $this->module->info;
		$this->breadcrumbs[] = ['label' => $infoModulo['txt_nome'], 'url' => Url::toRoute($infoModulo['txt_url'])];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Módulos', 'url' => Url::toRoute(['/admin/modulos', 'cod_modulo' => $cod_modulo])];
		$this->breadcrumbs[] = ['label' => 'Gerenciar Funcionalidades', 'url' => Url::toRoute(['/admin/funcionalidades', 'cod_modulo' => $cod_modulo])];
		$this->breadcrumbs[] = ['label' => $this->titulo];

		if ($model->cod_perfil_fk) {
			$listaAcao = TabAcoesSearch::find()->where('dte_exclusao is null');

			$model->lista_acao = $this->actionListarAcao($model->cod_perfil_fk, $model->cod_funcionalidade_fk, false);
		}

		return $this->render('admin', compact('model', 'listaAcoes', 'listaPerfis', 'listaAcao', 'cod_modulo'));
	}

	/**
	 * Finds the RlcPerfisFuncionalidadesAcoes model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return RlcPerfisFuncionalidadesAcoes the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = RlcPerfisFuncionalidadesAcoes::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}

	}

	/**
	 * Monta a lista de Perfis x Funcionalidades x Acoes para restricao do usuario.
	 * @param Integer $cod_perfil_fk
	 * @param Integers $cod_usuario_fk
	 */
	public function actionListarAcao($cod_perfil_fk, $cod_funcionalidade_fk = null, $ajax = true)
	{
		// Realizar a busca das funcionalidades x Ações restringidas ao usuário

		$arrayPerfilFuncAcao = [];

		if ($cod_funcionalidade_fk) {
			// itens selecionados

			$perfilFuncAcoes = RlcPerfisFuncionalidadesAcoesSearch::findAllAsArray(
					["cod_perfil_fk" => $cod_perfil_fk, "cod_funcionalidade_fk" => $cod_funcionalidade_fk]);

			if ($perfilFuncAcoes) {
				foreach ($perfilFuncAcoes as $perfilFuncAcao) {

					$arrayPerfilFuncAcao[] = $perfilFuncAcao['cod_acao_fk'];
				}
			}
		}

		$listaAcao = TabAcoesSearch::find()->where('dte_exclusao is null');

		$model = new RlcPerfisFuncionalidadesAcoesSearch();

		$model->lista_acao = Json::encode($arrayPerfilFuncAcao);
		if ($ajax) {
			return $this->renderAjax('_lista_acoes', [
					'model'		 => $model,
					'listaAcao'	 => $listaAcao
			]);
		} else {
			return $model->lista_acao;
		}
	}

}
