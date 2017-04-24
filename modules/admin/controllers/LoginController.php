<?php

namespace app\modules\admin\controllers;

use Yii;
use projeto\web\Controller;
use app\modules\admin\models\MdlUsuarios;
use app\modules\admin\models\TabUsuariosSearch;
use app\models\TabEstadosSearch;
use app\models\TabPrestadoresSearch;
use app\models\TabMunicipiosSearch;
use app\modules\admin\models\TabModulosSearch;
use app\modules\admin\models\TabPerfisSearch;
use app\modules\admin\models\RlcUsuariosPerfisSearch;
use app\modules\admin\models\VisUsuariosPerfisSearch;

class LoginController extends Controller
{

	public $layout = '//publico';

	public function behaviors() {
	
		return [
			'access' => [
				'class' => \projeto\filters\AccessControl::className(),
				'only' => ['index', 'logout'],
			],
		];
	}

	public function actionIndex() {

		$this->titulo = 'Acesso ao sistema';
		$this->subTitulo = 'Informe os dados abaixo para acessar o sistema:';

//		if (!$this->user->isGuest) {
//			return $this->goHome();
//		}

		$model = new MdlUsuarios();

		if ($this->isPost) {

			
			if ($model->load($this->request->post()) && $model->login()) {

				if (!TabUsuariosSearch::atualizarQtdAcesso()) {
					$this->session->setFlash('danger', 'Falha na autenticação, verifique os erros abaixo.');
				} else {
					Yii::trace('[projeto] Usuário realizou login no sistema');
					
					if (Yii::$app->user->identity->txt_tipo_login == '3') {
                    				return $this->redirect(['/gestao/prefeituras/admin', 'id' => Yii::$app->user->identity->cod_prestador_fk]);
					} else {
						$this->session->setFlash('success', 'Autenticação realizada com sucesso!');
                        
                        $modulos = VisUsuariosPerfisSearch::getModulosPerfisUsuario($this->user->identity->getId());
                        $perfil = RlcUsuariosPerfisSearch::find()
                            ->select([TabPerfisSearch::tableName() . '.txt_perfil_prestador'])
                            ->innerJoin(TabPerfisSearch::tableName(), TabPerfisSearch::tableName() . '.cod_perfil = ' . RlcUsuariosPerfisSearch::tableName() . '.cod_perfil_fk')
                            ->where(['=', 'cod_usuario_fk', $this->user->identity->getId()])
                            ->andWhere(RlcUsuariosPerfisSearch::tableName() . '.dte_exclusao IS NULL')
                            ->andWhere(TabPerfisSearch::tableName() . '.dte_exclusao IS NULL')
                            ->asArray()
                            ->one();
                        
                        if (count($modulos) > 1 || ($perfil['txt_perfil_prestador'] == 1 && Yii::$app->user->identity->qtd_acesso <= 1)) {
                            return $this->redirect(['/home']);
                        } else {
                            return $this->redirect(['/'.$modulos[0]['modulo_id']]);
                        }
					}
				}
			} else {
				$this->session->setFlash('danger', 'Falha na autenticação, verifique os erros abaixo.');
			}
		}

		return $this->render('index', [
			'model' => $model,
		]);
	}

	public function actionLogout()
	{
		$this->user->identity->ativarInativaSessao(false);
		$this->user->logout();

		$this->session->setFlash('success', 'Você saiu do sistema com sucesso!');
		return $this->redirect(['/']);
	}

	public function actionRegister($hash = null)
    {
//		$hash = $this->app->security->generatePasswordHash(37639);
//		\projeto\Util::dd($hash);
//		$modulo = TabModulosSearch::findOneAsArray(['id' => 'drenagem']);
//		\projeto\Util::dd($modulo);
						
        if (!$hash) {
            $this->session->setFlash('warning', 'Não existe hash para identificar um prestador.');
            return $this->response->redirect(['/entrar']);
        }
		else {
			
			$prestador = \app\models\TabPrestadoresSearch::findOneAsArray(['txt_hash_inicio_coleta' => $hash]);
			
			### VERIFICA SE O SISTEMA ESTÁ FECHADO P/ NÃO INICIADOS ###
			$modulo = \app\modules\admin\models\TabModulos::findOne(['id' => 'drenagem']);
			$externo = \app\models\TabParametrosSearch::findOne([
				'num_ano_ref' => Yii::$app->params['ano-ref'], 
				'sgl_parametro' => 'sistema-bloqueio-usr-externo', 
				'modulo_fk' => $modulo->cod_modulo,
			]);
			if ($externo->vlr_parametro == '1') {
				$externoSit = \app\models\TabParametrosSearch::findOne([
					'num_ano_ref' => Yii::$app->params['ano-ref'], 
					'sgl_parametro' => 'sistema-bloqueio-usr-externo-sit', 
					'modulo_fk' => $modulo->cod_modulo,
				]);
				
				$munLiberado =  false;
				$externoMun = \app\models\TabParametrosSearch::findOne([
					'num_ano_ref' => Yii::$app->params['ano-ref'], 
					'sgl_parametro' => 'sistema-bloqueio-usr-externo-mun', 
					'modulo_fk' => $modulo->cod_modulo
				]);
				if ($externoMun->vlr_parametro) {
					$arrMun = \yii\helpers\Json::decode($externoMun->vlr_parametro);
					$munLiberado = in_array($prestador['cod_municipio_fk'], $arrMun);
				}
				
				// fechado para não iniciados
				$arrSit = json_decode($externoSit->vlr_parametro, true);
				if (in_array('55@0', $arrSit) && !$munLiberado) {
					$this->session->setFlash('warning', 'ATENÇÃO: no momento o sistema se encontra fechado para novos acessos. Para maiores informações entre em contato conosco.');
					return $this->response->redirect(['/entrar']);
				}
			}
			### /VERIFICA SE O SISTEMA ESTÁ FECHADO P/ NÃO INICIADOS ###
			
            $cod_prestador = ($prestador['cod_prestador']) ? $prestador['cod_prestador'] : null;
            
            $flag = 0;
            if ($cod_prestador == null || !Yii::$app->security->validatePassword("$cod_prestador", $hash)) {
                $flag = 1;
            }
            
            if ($flag != 0) {
                $this->session->setFlash('warning', 'Não existe prestador registrado com o hash informado.');
                return $this->response->redirect(['/entrar']);
            } else {
                // Verifica se existe um usuário administrador vinculado ao prestador
                $usuario = TabUsuariosSearch::find()
                    ->innerJoin('acesso.rlc_usuarios_perfis as up', 'cod_usuario=up.cod_usuario_fk')
                    ->innerJoin('acesso.tab_perfis as p', 'up.cod_perfil_fk=p.cod_perfil')
                    ->where([
                        'cod_prestador_fk'       => $cod_prestador,
                        'p.txt_perfil_prestador' => '1'
                    ])
                    ->andwhere(TabUsuariosSearch::tableName() . '.dte_exclusao IS NULL')
                    ->orderBy('acesso.tab_usuarios.dte_inclusao desc')
                    ->one();

                if ($usuario) {
                    // Informa que já existe um usuario administrador vinculado ao prestador
                    $msg = "Existe um usuário com o perfil de administrador vinculado a este prestador. "
                        . "E somente o administrador <b>" . $usuario->txt_nome . "</b> poderá lhe registrar. "
                        ."Entre em contato pelo telefone "
                        . "<b>{$usuario->num_fone}</b> e/ou pelo e-mail "
                        . "<b>{$usuario->txt_email}</b>.";

                    $this->session->setFlash('warning', $msg);
                    return $this->response->redirect(['/entrar']);
                } else {
                    $model = new MdlUsuarios();
                    $model->scenario = 'register';

                    if ($hash) {
                        $municipio               = TabMunicipiosSearch::find()->where(['cod_municipio' => $prestador['cod_municipio_fk']])->asArray()->one();
                        $model->uf               = $municipio['sgl_estado_fk'];
                        $model->municipio        = $municipio['cod_municipio'];
                        $model->cod_prestador_fk = $cod_prestador;

                        $modulo = TabModulosSearch::findOneAsArray(['id' => 'drenagem']);
                        $perfil = TabPerfisSearch::findOneAsArray(['txt_perfil_prestador' => 1, 'cod_modulo_fk' => $modulo['cod_modulo']]);
                        $model->cod_perfil_fk    = $perfil['cod_perfil'];
                        $model->modulos          = [$modulo['cod_modulo']];

                        $listaEstado             = TabEstadosSearch::find()->where(['sgl_estado' => $municipio['sgl_estado_fk']])->all();
                        $listaMunicipio          = TabMunicipiosSearch::find()->where(['cod_municipio' => $municipio['cod_municipio']])->all();
                        $listaPrestador          = TabPrestadoresSearch::find()->where(['cod_prestador' => $cod_prestador])->all();
                        $listaPerfil             = TabPerfisSearch::find()->all();
                        $listaModulo             = [];
                    } else {
                        $listaEstado    = TabEstadosSearch::find()->orderBy('txt_nome')->all();
                        $listaMunicipio = [];
                        $listaPrestador = [];
                        $listaPerfil    = [];
                        $listaModulo    = [];
                    }

                    if ($model->load(Yii::$app->request->post())) {
                        if ($model->validate()) {
                            $transaction = $this->db->beginTransaction();
                            try {
                                $txtSenha = $model->txt_senha;
                                if ($model->save()) {
                                    $listaPerfil = TabPerfisSearch::find()
                                        ->innerJoin("acesso.tab_modulos as m", "acesso.tab_perfis.cod_modulo_fk = m.cod_modulo")
                                        ->innerJoin('public.rlc_modulos_prestadores as mp', 'm.cod_modulo = mp.cod_modulo_fk')
                                        ->where([
                                            'mp.cod_prestador_fk'  => $model->cod_prestador_fk,
                                            'txt_perfil_prestador' => '1'
                                        ])->all();

                                    foreach ($listaPerfil as $key => $value) {
                                        $usuarioPerfil = new RlcUsuariosPerfisSearch();
                                        $usuarioPerfil->cod_perfil_fk  = $value->cod_perfil;
                                        $usuarioPerfil->cod_usuario_fk = $model->cod_usuario;
                                        $usuarioPerfil->save();
                                    }

                                    // Insere o uso do hash na tabela de prestadores
                                    $prestadorHash = TabPrestadoresSearch::findOne($cod_prestador);
                                    $prestadorHash->bln_hash_inicio_coleta_usado = true;
                                    $prestadorHash->save(false);

                                    // insere dados do encarregado da informação
                                    $encarregado = \app\models\RlcModulosPrestadoresSearch::findOne(['cod_prestador_fk' => $cod_prestador, 'cod_modulo_fk' => $modulo['cod_modulo']]);
                                    $encarregado->cp032 = $model->txt_nome;
                                    $encarregado->cp036 = $model->num_fone;
                                    $encarregado->cp042 = $model->txt_email;
                                    $encarregado->save(false);
                                }

                                $this->session->setFlash('success', 'Registro de novo usuário realizado com sucesso. Um e-mail de confirmação foi enviado para <b>' . $model->txt_email . '</b>.');
                                $transaction->commit();

                                // Envia e-mail com os dados de acesso, confirmando o registro do novo usuário
                                $this->app->mailer->registroNovoUsuario($model, $txtSenha);

                                return $this->response->redirect(['/entrar']);
                            } catch (Exception $e) {
                                $this->session->setFlash('danger', $e->getMessage());
                                $transaction->rollBack();
                            }
                        }
                    }

                    if ($model->uf) {
						
                        $listaMunicipio = TabMunicipiosSearch::find()->select(['cod_municipio', new \yii\db\Expression("CONCAT(sgl_estado_fk,'-', txt_nome) as txt_nome")])->where(['sgl_estado_fk' => $model->uf])->orderBy('txt_nome')->all();
					
                        if ($model->municipio) {
                            $listaPrestador = TabPrestadoresSearch::find()->where(['cod_municipio_fk' => $model->municipio])->orderBy('dg002')->all();
                            if ($model->cod_prestador_fk) {
                                $listaModulo = TabModulosSearch::find()->joinWith('rlcModulosPrestadores')->where("cod_prestador_fk='{$model->cod_prestador_fk}'")->orderBy('txt_nome')->all();
                            }
                        }
                    }

                    return $this->render('register', [
                        'cod_prestador'  => $cod_prestador,
                        'model'          => $model,
                       // 'listaEstado'    => $listaEstado,
                        'listaMunicipio' => $listaMunicipio,
                        'listaPrestador' => $listaPrestador,
                        'listaPerfil'    => $listaPerfil,
                        'listaModulo'    => $listaModulo,
                    ]);
                }
            }
        }
	}
    
	public function actionRecuperarSenha($token = null)
	{
		if ($token) {
			$model				 = TabUsuariosSearch::find()->where(['cod_usuario' => $token])->one();
			$model->txt_senha	 = '';
			$model->scenario	 = TabUsuariosSearch::SCENARIO_RECUPERAR_SENHA_CONFIRMACAO;
			$view				 = 'recuperar_senha_confirma';
		} else {
			$model			 = new TabUsuariosSearch();
			$model->scenario = TabUsuariosSearch::SCENARIO_RECUPERAR_SENHA_SOLICITACAO;
			$view			 = 'recuperar_senha';
		}

		// solicitacao de recuperacao de senha
		if ($model->scenario == TabUsuariosSearch::SCENARIO_RECUPERAR_SENHA_SOLICITACAO) {
			if ($model->load(Yii::$app->request->post()) && $model->validate()) {

				// atualiza o atributo txt_trocar_senha
				$usuario = TabUsuariosSearch::find()->where(['txt_email' => $model->txt_email])->one();

				// se nao existir retorna alerta
				if (is_null($usuario)) {
					$this->session->setFlash('danger', 'Usuário não localizado.');
				} else {
					// se existir altera o atributo para 1 e envia email
					$usuario->txt_trocar_senha = '1';
					$usuario->save();

					$this->app->mailer->redefinirSenha($usuario);
					$this->session->setFlash('success', 'Uma mensagem com instruções foi enviada para seu e-mail.');

					// limpa o model
					$model = new TabUsuariosSearch();
				}
			}
		}

		// alteracao da senha de acesso ao sistema
		if ($model->scenario == TabUsuariosSearch::SCENARIO_RECUPERAR_SENHA_CONFIRMACAO) {

			if ($model->txt_trocar_senha == '0') {
				$this->session->setFlash('danger', 'Não existe nenhuma solicitação de recuperação de senha para este usuário.');
			} else if ($model->load(Yii::$app->request->post()) && $model->validate()) {
				$model->save();

				$this->session->setFlash('success', 'Senha alterada com sucesso.');
				return $this->redirect(['/home']);
			}
		}

		return $this->render($view, [
			'model' => $model,
		]);
	}

	public function actionAlterarSenha()
	{
		$this->titulo	 = 'Alterar senha';
		$this->subTitulo = null;
		$this->layout	 = null;

		$model				 = $this->findModel(Yii::$app->user->identity->cod_usuario);
		$model->txt_senha	 = null;
		$model->scenario	 = TabUsuariosSearch::SCENARIO_ALTERAR_SENHA;

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {

			$transaction = $this->db->beginTransaction();
			try {

				if ($model->save()) {
					$this->session->setFlash('success', 'Senha alterada com sucesso.');
					$model->txt_senha_atual		 = null;
					$model->txt_senha			 = null;
					$model->txt_senha_confirma	 = null;
					$transaction->commit();
				}
			} catch (Exception $e) {
				$transaction->rollBack();
				throw $e;
			}
		}

		return $this->render('alterar_senha', [
			'model' => $model,
		]);
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
		if (($model = TabUsuariosSearch::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

}
