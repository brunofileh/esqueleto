<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\TabUsuarios;
use app\modules\admin\models\TabUsuariosSearch;
use app\modules\admin\models\TabUsuariosPrestadoresSearch;
use app\modules\admin\models\VisModulosPerfisMenusFuncionalidadesSearch;
use app\modules\admin\models\RlcUsuariosPerfis;
use app\modules\admin\models\RlcUsuariosPerfisSearch;
use app\modules\admin\models\RlcPerfisFuncionalidadesAcoesSearch;
use app\modules\admin\models\TabRestricoesUsuarios;
use app\modules\admin\models\TabRestricoesUsuariosSearch;
use app\modules\admin\models\TabModulosSearch;
use app\modules\admin\models\TabPerfisSearch;
use app\modules\admin\models\VisUsuariosPerfisSearch;
use app\models\TabPrestadoresSearch;
use projeto\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UsuariosPrestadoresController implements the CRUD actions for TabUsuarios model.
 */
class UsuariosPrestadoresController extends Controller
{

    /**
     * Lists all TabUsuarios models.
     * @return mixed
     */
    public function actionIndex()
    {		
		$searchModel = new TabUsuariosPrestadoresSearch();
		
		$perfil = $this->module->getInfo()['usuario-perfil']['cod_perfil_fk'];
		$TabPerfisSearch = TabPerfisSearch::find()
			->select('txt_perfil_prestador')
			->where('cod_perfil = '.$perfil.'')
			->one();
		$codPrestadorFk = null;
		if ($TabPerfisSearch['txt_perfil_prestador'] != 0) {
			$usuario = $this->module->getInfo()['usuario-perfil']['cod_usuario_fk'];
			
			$TabUsuariosPrestadoresSearch = TabUsuariosPrestadoresSearch::find()
				->select('cod_prestador_fk')
				->where('cod_usuario = '.$usuario.'')
				->one();							
			$codPrestadorFk = $TabUsuariosPrestadoresSearch['cod_prestador_fk'];
			
			$searchModel->cod_prestador_fk = $codPrestadorFk;
		}		

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		$this->titulo = 'Gerenciar Usuários Prestadores';
		$this->subTitulo = '';		
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'codPrestadorFk' => $codPrestadorFk,
        ]);
    }

    /**
     * Displays a single TabUsuarios model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
		$this->titulo = 'Detalhar Usuários Prestadores';
		$this->subTitulo = '';
		
		$dadosModulos = VisModulosPerfisMenusFuncionalidadesSearch::find()
			->distinct()
			->select(['cod_modulo', 'nome_modulo'])
			->Where('txt_perfil_prestador > \'0\'')
			->orderBy('nome_modulo')
			->all();
		foreach ($dadosModulos as $value) {			
			$dadosFuncionalidades = VisModulosPerfisMenusFuncionalidadesSearch::find()
				->distinct(true)
				->select([
					'cod_modulo', 'nome_modulo', 'cod_funcionalidade'
					, 'nome_menu_pai', 'nome_menu', 'num_ordem'
				])
				->Where('txt_perfil_prestador = \'1\' AND cod_modulo = '.$value['cod_modulo'].'')
				->orderBy('nome_modulo, nome_menu_pai, num_ordem')
				->all();
			foreach ($dadosFuncionalidades as $valueFL) {
				$nome_funcionalidade = ($valueFL['nome_menu_pai'] == '') ? $valueFL['nome_menu'] : $valueFL['nome_menu_pai']." - ".$valueFL['nome_menu'];				
				$arrFuncionalidades[$valueFL['cod_modulo']][] = $nome_funcionalidade;
			}
						
			$arrModulos[$value['cod_modulo']] = [
				'cod_modulo' => $value['cod_modulo'],
				'nome_modulo' => $value['nome_modulo']
			];
		}

		$dadosPerfis = RlcUsuariosPerfisSearch::perfisModulosPorUsuario($id);
		foreach ($dadosPerfis as $value) {
			$arrPerfis[$value['cod_modulo_fk']] = [
				'value' => $value['txt_nome']
			];
		}		

		$dadosRestricoes = TabRestricoesUsuariosSearch::restricoesFuncionalidadesPorUsuario($id);
		$arrFuncionalidadesRestritas = [];
		foreach ($dadosRestricoes as $valueR) {
			$dadosFuncionalidadesRestritas = VisModulosPerfisMenusFuncionalidadesSearch::find()
				->distinct(true)
				->select([
					'cod_modulo', 'nome_modulo', 'cod_funcionalidade'
					, 'nome_menu_pai', 'nome_menu', 'num_ordem'
				])
				->Where('txt_perfil_prestador = \'1\' AND cod_funcionalidade = '.$valueR['cod_funcionalidade_fk'].'')
				->orderBy('nome_modulo, nome_menu_pai, num_ordem')
				->all();
			foreach ($dadosFuncionalidadesRestritas as $valueFR) {
				$nome_funcionalidade = ($valueFR['nome_menu_pai'] == '') ? $valueFR['nome_menu'] : $valueFR['nome_menu_pai']." - ".$valueFR['nome_menu'];				
				$arrFuncionalidadesRestritas[$valueFR['cod_modulo']][] = $nome_funcionalidade;
			}			
		}
		
		$arrFuncionalidadesLiberadas = [];
		foreach ($arrFuncionalidades as $key => $value) {
			if (isset($arrFuncionalidadesRestritas[$key]))
				$arrFuncionalidadesLiberadas[$key] = array_diff($arrFuncionalidades[$key], $arrFuncionalidadesRestritas[$key]);
			else
				$arrFuncionalidadesLiberadas[$key] = $arrFuncionalidades[$key];

			sort($arrFuncionalidadesLiberadas[$key]);
			if (count($arrFuncionalidadesLiberadas[$key]) == 0)
				unset($arrFuncionalidadesLiberadas[$key]);						
		}
			
        return $this->render('view', [
            'model' => $this->findModel($id),
			'arrModulos' => $arrModulos,
			'arrPerfis' => $arrPerfis,
			'arrFuncionalidadesRestritas' => $arrFuncionalidadesRestritas,
			'arrFuncionalidadesLiberadas' => $arrFuncionalidadesLiberadas,
        ]);
    }

	
	
	/**
	 * Creates e Updates a new TabUsuarios  model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionAdmin( $id = null )
	{
		
		if ($id)
		{		
			$model = $this->findModel($id);
			$acao = 'update';
			$this->titulo = 'Alterar Usuários Prestadores';
			$this->subTitulo = '';
		}
		else
		{		
			$acao = 'create';
			$model = new TabUsuariosPrestadoresSearch();
			$this->titulo = 'Incluir Usuários Prestadores';
			$this->subTitulo = '';
		}
		
		$model->scenario = 'admin';
		
		$perfil = $this->module->getInfo()['usuario-perfil']['cod_perfil_fk'];
		$TabPerfisSearch = TabPerfisSearch::find()
			->select('txt_perfil_prestador')
			->where('cod_perfil = '.$perfil.'')
			->one();
		$codPrestadorFk = null;
		if ($TabPerfisSearch['txt_perfil_prestador'] != 0) {
			$usuario = $this->module->getInfo()['usuario-perfil']['cod_usuario_fk'];
			
			$TabUsuariosPrestadoresSearch = TabUsuariosPrestadoresSearch::find()
				->select('cod_prestador_fk')
				->where('cod_usuario = '.$usuario.'')
				->one();							
			$codPrestadorFk = $TabUsuariosPrestadoresSearch['cod_prestador_fk'];
			
			$listaPrestadores = TabPrestadoresSearch::find()
				->select(['cod_prestador', '"dg002" || \' - \' || "dg003" AS txt_nome'])
				->Where(['=', 'cod_prestador', $codPrestadorFk])
				->orderBy('txt_nome')
				->all();
			$model->cod_prestador_fk = $codPrestadorFk;
			
			$dadosModulos = VisUsuariosPerfisSearch::find()
				->distinct(true)
				->select(['cod_modulo_fk AS cod_modulo', 'nome_modulo'])
				->Where('txt_perfil_prestador = \'1\'')
				->andWhere('cod_usuario_fk = ' . $usuario)
				->orderBy('nome_modulo')
				->all();		
		}		
		
		if ($codPrestadorFk == null) {
			$listaPrestadores = TabPrestadoresSearch::find()
				->select(['cod_prestador', '"dg002" || \' - \' || "dg003" AS txt_nome'])
				->orderBy('txt_nome')
				->asArray()
				->all();			
			
			$dadosModulos = VisModulosPerfisMenusFuncionalidadesSearch::find()
				->distinct(true)
				->select(['cod_modulo', 'nome_modulo'])
				->Where('txt_perfil_prestador > \'0\'')
				->orderBy('nome_modulo')
				->asArray()
				->all();
		}
		
		foreach ($dadosModulos as $value) {
			$arrModulos[$value['cod_modulo']] = [
				'cod_modulo' => $value['cod_modulo'],
				'nome_modulo' => $value['nome_modulo']
			];
		}			
			
		foreach ($dadosModulos as $modulo) {
			$dadosPerfis = VisModulosPerfisMenusFuncionalidadesSearch::find()
				->distinct(true)
				->select(['cod_modulo', 'nome_modulo', 'cod_perfil', 'nome_perfil'])
				->Where('txt_perfil_prestador > \'0\' AND cod_modulo = '.$modulo['cod_modulo'].'')
				->orderBy('nome_modulo, nome_perfil')
				->all();
			foreach ($dadosPerfis as $perfil) {				
				$selected = '';
				
				if (!$model->isNewRecord) {
					$exRlcUsuariosPerfis = RlcUsuariosPerfis::find()
						->where('cod_usuario_fk = '.$model->cod_usuario.' AND cod_perfil_fk = '.$perfil['cod_perfil'].' AND dte_exclusao IS NULL')
						->one();
					if ($exRlcUsuariosPerfis)
						$selected = 'selected';
				}
				
				if (Yii::$app->request->post()) {
					if ($_POST['TabUsuariosPrestadoresSearch']['cod_perfil_fk'][$modulo['cod_modulo']] == $perfil['cod_perfil'])
						$selected = 'selected';
				}
				
				$arrPerfis[$modulo['cod_modulo']][$perfil['cod_perfil']] = [
					'value' => $perfil['nome_perfil'],
					'selected' => $selected,
				];
			}
		}
		
		foreach ($dadosModulos as $modulo) {
			$dadosFuncionalidades = VisModulosPerfisMenusFuncionalidadesSearch::find()
				->distinct(true)
				->select([
					'cod_modulo', 'nome_modulo', 'cod_funcionalidade'
					, 'nome_menu_pai', 'nome_menu', 'num_ordem'
				])
				->Where('txt_perfil_prestador = \'1\' AND cod_modulo = '.$modulo['cod_modulo'].'')
				->orderBy('nome_modulo, nome_menu_pai, num_ordem')
				->all();
			foreach ($dadosFuncionalidades as $funcionalidade) {
				$nome_funcionalidade = ($funcionalidade['nome_menu_pai'] == '') ? $funcionalidade['nome_menu'] : $funcionalidade['nome_menu_pai']." - ".$funcionalidade['nome_menu'];
				
				$checked = 'checked';				
				if (!$model->isNewRecord) {
					$TabRestricoesUsuariosSearch = TabRestricoesUsuariosSearch::restricoesFuncionalidadesPorUsuario($model['cod_usuario']);
					
					foreach ($TabRestricoesUsuariosSearch as $value) {
						$arrRestricoes[] = $value['cod_funcionalidade_fk'];
					}
					
					if (isset($arrRestricoes)) {
						if (in_array($funcionalidade['cod_funcionalidade'], $arrRestricoes))
							$checked = '';
					}
				}
								
				if (Yii::$app->request->post()) {
					if (isset($_POST['TabUsuariosPrestadoresSearch']['cod_funcionalidade_fk'][$funcionalidade['cod_modulo']])) {
						if (!in_array($funcionalidade['cod_funcionalidade'], $_POST['TabUsuariosPrestadoresSearch']['cod_funcionalidade_fk'][$funcionalidade['cod_modulo']]))
							$checked = '';
					} else {
						$checked = '';
					}
				}				
				
				$arrFuncionalidades[$modulo['cod_modulo']][$funcionalidade['cod_funcionalidade']] = [
					'value' => $nome_funcionalidade,
					'checked' => $checked,
				];
			}
		}		
		
		$modelTabUsuariosSearch = new TabUsuariosSearch();
		
	
		if ($model->load(Yii::$app->request->post()))
		{
			
			$arrPerfisPost = $_POST['TabUsuariosPrestadoresSearch']['cod_perfil_fk'];
			
			$arrPostFuncionalidades = [];
			$arrPostFuncionalidades = $_POST['TabUsuariosPrestadoresSearch']['cod_funcionalidade_fk'];

			$arrModulosErrors = [];
			foreach ($arrPerfisPost as $keyP => $valueP) {				
				if(($valueP == '' && (isset($arrPostFuncionalidades[$keyP]))) || ($valueP != '' && (!isset($arrPostFuncionalidades[$keyP])))) {
					$arrModulosErrors[] = TabModulosSearch::find()
						->where(['cod_modulo' => $keyP])
						->one()
						->txt_nome;
				}
			}
			
			if (count($arrModulosErrors) > 0) {
				$this->session->setFlash('danger', 'Erro: Existem Módulos ('.implode(', ', $arrModulosErrors).') com campos que não podem ficar em branco.');
			} else {
				$transaction = $this->db->beginTransaction();
				try {
					$isNewRecord = $model->isNewRecord;
					
					if ($isNewRecord) {
						$model->txt_senha = substr(md5(date('YmdHis')), 0, 6);
						$txtSenha = $model->txt_senha;
					}
					
					if ($model->save()) {
						
						// Inserir perfis para cada módulo na rlc_usuarios_perfis
						RlcUsuariosPerfisSearch::updateAll(['dte_exclusao' => 'now()'], 'cod_usuario_fk = '.$model->cod_usuario.'');
						foreach ($arrPerfisPost as $value) {												
							if ($value != '') {
								if (RlcUsuariosPerfisSearch::find()->where('cod_usuario_fk = '.$model->cod_usuario.' and cod_perfil_fk = '.$value.'')->one()) {
									RlcUsuariosPerfisSearch::updateAll(['dte_exclusao' => null], 'cod_usuario_fk = '.$model->cod_usuario.' and cod_perfil_fk = '.$value.'');
								} else {
									$modelRlcUsuariosPerfis = new RlcUsuariosPerfis();
									$modelRlcUsuariosPerfis->cod_usuario_fk = $model->cod_usuario;
									$modelRlcUsuariosPerfis->cod_perfil_fk = $value;
									$modelRlcUsuariosPerfis->save();
								}												
							}
						}

						// Saber o que não foi selecionado pelo usuário e pegar o cod_perfil_funcionalidade_acao
						// Inserir restrições para módulo do usuário na tab_restricoes_usuarios

						$arrPrincipalFuncionalidades = [];
						foreach ($arrFuncionalidades as $key => $value) {
							$arrPrincipalFuncionalidades[$key] = array_keys($value);
						}

						$arrResultFuncionalidades = [];
						foreach ($arrPrincipalFuncionalidades as $key => $value) {						
							if (isset($arrPostFuncionalidades[$key]))
								$arrResultFuncionalidades[$key] = array_diff($arrPrincipalFuncionalidades[$key], $arrPostFuncionalidades[$key]);
							else
								$arrResultFuncionalidades[$key] = $arrPrincipalFuncionalidades[$key];

							sort($arrResultFuncionalidades[$key]);
							if (count($arrResultFuncionalidades[$key]) == 0)
								unset($arrResultFuncionalidades[$key]);						
						}

						$arrDifPerfis = [];
						foreach ($arrPerfisPost as $keyPP => $valuePP) {							
							if ($valuePP == '') {							
								foreach ($arrPerfis[$keyPP] as $keyP => $valueP) {
									$arrDifPerfis[] = $keyP;									
								}
							}							
						}

						TabRestricoesUsuariosSearch::updateAll(['dte_exclusao' => 'now()'], 'cod_usuario_fk = '.$model->cod_usuario.'');
						foreach ($arrResultFuncionalidades as $key => $value1) {
							foreach ($arrResultFuncionalidades[$key] as $value2) {
								$cod_perfil_fk = $_POST['TabUsuariosPrestadoresSearch']['cod_perfil_fk'][$key];
								if ($cod_perfil_fk != '') {									
									$rlcPerfisFuncionalidadesAcoes = RlcPerfisFuncionalidadesAcoesSearch::find()->where('cod_perfil_fk = '.$cod_perfil_fk.' AND cod_funcionalidade_fk = '.$value2.' and dte_exclusao IS NULL')->all();
								} else {								
									$rlcPerfisFuncionalidadesAcoes = RlcPerfisFuncionalidadesAcoesSearch::find()->where('cod_perfil_fk IN ('.implode(', ', $arrDifPerfis).') AND cod_funcionalidade_fk = '.$value2.' and dte_exclusao IS NULL')->all();
								}
								
								if($rlcPerfisFuncionalidadesAcoes) {
									foreach ($rlcPerfisFuncionalidadesAcoes as $value3) {										
										if (TabRestricoesUsuariosSearch::find()->where('cod_usuario_fk = '.$model->cod_usuario.' and cod_perfil_funcionalidade_acao_fk = '.$value3->cod_perfil_funcionalidade_acao.'')->one()) {
											TabRestricoesUsuariosSearch::updateAll(['dte_exclusao' => null], 'cod_usuario_fk = '.$model->cod_usuario.' and cod_perfil_funcionalidade_acao_fk = '.$value3->cod_perfil_funcionalidade_acao.'');
										} else {
											$modelTabRestricoesUsuarios = new TabRestricoesUsuarios();
											$modelTabRestricoesUsuarios->cod_usuario_fk = $model->cod_usuario;
											$modelTabRestricoesUsuarios->cod_perfil_funcionalidade_acao_fk = $value3->cod_perfil_funcionalidade_acao;
											$modelTabRestricoesUsuarios->save();
										}																					
									}
								}							
							}
						}
						
						
						$transaction->commit();
						// depois do commit garantir q salvou antes de enviar o email
						if ($isNewRecord) {
							$this->app->mailer->registroNovoUsuario($model, $txtSenha);
						}
						
						$this->session->setFlashProjeto( 'success', $acao );
						return $this->redirect( ['view', 'id' => $model->cod_usuario ]);
					}
				} catch (Exception $e) {
					$transaction->rollBack();
					throw $e;
				}				
			}
		}

		return $this->render( 'admin', [
			'model' => $model,
			'modelTabUsuariosSearch' => $modelTabUsuariosSearch,
			'listaPrestadores' => $listaPrestadores,
			'arrModulos' => $arrModulos,
			'arrPerfis' => $arrPerfis,
			'arrFuncionalidades' => $arrFuncionalidades,
		]);
	}
	
	
	
    /**
     * Creates a new TabUsuarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TabUsuarios();

		$this->titulo = 'Incluir Usuario';
		$this->subTitulo = '';
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			
			$this->session->setFlashProjeto( 'success', 'update' );
			
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
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
	
		$this->titulo = 'Alterar Usuario';
		$this->subTitulo = '';
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
		
			$this->session->setFlashProjeto( 'success', 'update' );
            
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
    public function actionDelete($id)
    {		
		$model = $this->findModel($id);
		$model->dte_exclusao = 'now()';
		
		if ($model->save()) {
			RlcUsuariosPerfisSearch::updateAll(['dte_exclusao' => 'now()'], 'cod_usuario_fk = ' . $id . '');
			TabRestricoesUsuariosSearch::updateAll(['dte_exclusao' => 'now()'], 'cod_usuario_fk = ' . $id . '');
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
    protected function findModel($id)
    {
        if (($model = TabUsuariosPrestadoresSearch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
