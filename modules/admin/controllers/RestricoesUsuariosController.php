<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\TabRestricoesUsuarios;
use app\modules\admin\models\TabRestricoesUsuariosSearch;
use app\modules\admin\models\TabUsuariosSearch;
use app\modules\admin\models\VisPerfisFuncionalidadesAcoes;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use projeto\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

/**
 * RestricoesUsuariosController implements the CRUD actions for TabRestricoesUsuarios model.
 */
class RestricoesUsuariosController extends Controller
{
    public $activeMenu = 'Usuário';
    /**
     * Lists all TabRestricoesUsuarios models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new TabRestricoesUsuariosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->titulo = 'Gerenciar Restrições de Usuários';

        return $this->render('index', [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TabRestricoesUsuarios model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->titulo = 'Detalhar Restrições de Usuário';

        return $this->render('view', [
                'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates e Updates a new TabRestricoesUsuarios  model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAdmin($cod_usuario_fk)
    {
        $usuario             = new TabUsuariosSearch();
        $model               = new TabRestricoesUsuariosSearch();
        $model->scenario     = $model::SCENARIO_RESTRICAO;
        $model->podeVincular = true;
        $this->titulo        = 'Restringir Acesso do Usuário';

        if ($cod_usuario_fk) {
            // Recupera os dados do usuario
            $usuario = TabUsuariosSearch::findOne(['cod_usuario' => $cod_usuario_fk]);
//            $this->subTitulo = 'Usuário: ' . $usuario->txt_nome;
        } else {
            $model->podeVincular = false;
            $this->session->setFlash('danger', 'Selecione ao menos um usuário.');
        }

        $arrayRestricoesUsuarios = [];
        if ($model->podeVincular) {
            if ($usuario->rlcUsuariosPerfis) {
                if ($model->load(Yii::$app->request->post())) {
                    if ($model->validate()) {
                        if ($model->modulo_perfil) {
                            $restricoesUsuarios = $model->searchRestricao('IN', $usuario->cod_usuario, $model->modulo_perfil);

                            if ($restricoesUsuarios) {
                                foreach ($restricoesUsuarios as $restricaoUsuario) {
                                    $arrayRestricoesUsuarios[] = $restricaoUsuario['cod_perfil_funcionalidade_acao'];
                                }
                            }
                        }
                        $transaction = $this->db->beginTransaction();

                        try {
                            if ($model->lista_restricoes) {
                                $lista = (Json::decode($model->lista_restricoes)) ? Json::decode($model->lista_restricoes) : [];
                                if (array_diff_assoc($arrayRestricoesUsuarios, $lista) || array_diff_assoc($lista, $arrayRestricoesUsuarios)) {
                                    // DELETA TODOS OS REGISTROS DA RESTRICAO_USUARIOS PARA O USUARIO/PERFIL
                                    if ($arrayRestricoesUsuarios) {
                                        TabRestricoesUsuariosSearch::deleteAll(['IN', 'cod_perfil_funcionalidade_acao_fk', $arrayRestricoesUsuarios]);
                                    }
                                    foreach ($lista as $restricao) {
                                        $restricaoUsuario                                    = new TabRestricoesUsuariosSearch();
                                        $restricaoUsuario->cod_usuario_fk                    = $usuario->cod_usuario;
                                        $restricaoUsuario->cod_perfil_funcionalidade_acao_fk = $restricao;

                                        if (!$restricaoUsuario->save()) {
                                            throw new \Exception('Erro ao salvar');
                                        }
                                    }
                                }
                            }

                            $this->session->setFlash('success', 'Restrição efetivada com sucesso.');
                            $transaction->commit();

                            return $this->redirect(Url::to(['usuarios/index']));
                        } catch (\Exception $e) {
                            $transaction->rollBack();
                            $this->session->setFlash('danger', $e->getMessage());
                        }
                    }
                }
            } else {
                $model->podeVincular = false;
                $this->session->setFlash('danger', 'O usuário precisa estar vinculado a pelo menos um perfil.');
            }
        }
        $this->breadcrumbs[] = ['label' => 'Controle de Acesso' , 'url' => Url::toRoute( '/admin' )];
        $this->breadcrumbs[] = ['label' => 'Usuário', 'url' => Url::toRoute('/admin/usuarios')];
        $this->breadcrumbs[] = $this->titulo;	

        $model->lista_restricoes = Json::encode($arrayRestricoesUsuarios);

        return $this->render('admin', [
                'usuario' => $usuario,
                'model'   => $model,
        ]);
    }

    /**
     * Deletes an existing TabRestricoesUsuarios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model               = $this->findModel($id);
        $model->dte_exclusao = 'NOW()';

        if ($model->save()) {

            $this->session->setFlashProjeto('success', 'delete');
        } else {

            $this->session->setFlashProjeto('danger', 'delete');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the TabRestricoesUsuarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TabRestricoesUsuarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TabRestricoesUsuariosSearch::findOne($id)) !== null) {
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
    public function actionListarFuncionalidades($cod_perfil_fk, $cod_usuario_fk)
    {
        // Realizar a busca das funcionalidades x Ações restringidas ao usuário

        if (empty($cod_perfil_fk) || empty($cod_usuario_fk)) {
            echo '';
        } else {
            // itens selecionados
            $restricoesUsuario      = TabRestricoesUsuariosSearch::findAllAsArray(["cod_usuario_fk" => $cod_usuario_fk]);
            $arrayRestricoesUsuario = [];

            if ($restricoesUsuario) {
                foreach ($restricoesUsuario as $restricaoUsuario) {

                    $arrayRestricoesUsuario[] = $restricaoUsuario['cod_perfil_funcionalidade_acao_fk'];
                }
            }

            $listaRestricoes = VisPerfisFuncionalidadesAcoes::find()->where("
												( 
													(
													 cod_perfil = {$cod_perfil_fk} 
													)  
												)
										")->select('cod_perfil_funcionalidade_acao, funcionalidade_acao')->orderBy('dsc_funcionalidade, dsc_acao');

            $model                   = new TabRestricoesUsuariosSearch();
            $model->lista_restricoes = Json::encode($arrayRestricoesUsuario);

            return $this->renderAjax('_lista_restricao', [
                    'model'           => $model,
                    'listaRestricoes' => $listaRestricoes
            ]);
        }
    }

    public function retornaLista($tipo, &$dados, $cod_perfil_fk, $cod_usuario_fk)
    {
        $model = new TabRestricoesUsuariosSearch();
        $itens = $model->searchRestricao($tipo, $cod_usuario_fk, $cod_perfil_fk);

        $lista = ArrayHelper::map($itens, 'cod_perfil_funcionalidade_acao', function($model)
            {
                $model = (Object) $model;
                return $model->dsc_funcionalidade . ' - ' . $model->dsc_acao;
            });
        foreach ($lista as $key => $valor) {
            $dados .= '<option value=' . $key . '>' . $valor . '</option>';
        }
    }

}
