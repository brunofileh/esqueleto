<?php

namespace projeto\web;

use Yii;
use app\modules\drenagem\models\TabParticipacoes;
use app\modules\drenagem\models\TabParticipacoesSearch;
use app\models\TabMunicipiosSearch;
use app\models\TabMunicipiosPopulacoes;
use app\models\TabMunicipiosDesastres;
use app\models\RlcModulosPrestadoresSearch;
use app\models\VisAtributosValores;
use app\models\VisGlossarios;
use app\models\VisConsultaPrestadoresSearch;
use app\modules\admin\models\TabUsuariosSearch;
use app\models\TabPrestadoresSearch;
use kartik\mpdf\Pdf;
use yii\helpers\Url;
use app\models\TabAtributosValoresSearch;
use app\models\TabBaciasHidrograficas;
use app\models\RlcMunicipiosBaciasHidrograficas;
use app\models\VwLogSearch;

class ColetaController extends Controller 
{
	public $where = [];
	public $pularVerificacaoPsvSelecionado = true;
	public $situacaoPreenchimento = null;
	public $isFormSendoUtilizadoPorOutroUsr = false;
	public $msgFormSendoUtilizadoPorOutroUsr = 'Você não tem permissão para salvar os dados deste formulário pois outro usuário está ativo no nomento.';
    public $searchModelLog;
    public $dataProviderLog;
    public $filtrosLog;
    public $paramsLog;
    public $glossarioForm;
    
    public function init()
	{
		parent::init();
		$this->validarAcessoPorAba();
	}
	
	protected function validarAcessoPorAba()
	{
		if (!$this->isPost && !$this->isAjax) {
			return;
		}
		$tabParticipacoes = $this->session->get('TabParticipacoes');
		$codpart = $this->request->post('codpart');
		
		if ((null !== $tabParticipacoes['cod_participacao'])
			&& null === $this->app->user->identity->cod_prestador_fk
			&& null !== $codpart
			&& sha1($tabParticipacoes['cod_participacao']) !== $codpart
		) {
			$m = "Parece que você está tentando utilizar a mesma sessão para trabalhar com mais de um município/prestador. "
				. "<br> No momento isto não é possível. <br> O município/prestador que está ativo será sempre o último "
				. "selecionado na busca de prestadores, <br> neste caso "
				. "<b>{$_SESSION['TabParticipacoes']['localizacao']['txt_nome']} - {$_SESSION['TabParticipacoes']['localizacao']['sgl_estado_fk']}</b>";
			
			$this->session->setAlert([
				'type' => 'error',
				'title' => 'Atenção',
				'html' => $m,
			]);
			$this->redirect(["/{$this->module->id}"]);
			\Yii::$app->end();
		}
	}
	
	public function validaAcessoFormulario()
	{
        if (($tabParticipacoes = $this->session->get('TabParticipacoes'))) {
            $nome_municipio = "{$tabParticipacoes['localizacao']['txt_nome']} - {$tabParticipacoes['localizacao']['sgl_estado_fk']}";
        }
        
        if ($this->situacaoPreenchimento['cod_situacao_preenchimento'] == TabAtributosValoresSearch::NAO_INICIADO) {
            if ($this->module->getInfo()['usuario-perfil']['cod_prestador_fk'] == null) {
				$this->session->setFlash('warning', 'Este prestador não iniciou a coleta, portanto você terá acesso somente aos formulários do menu "Cadastro Municipal". '
					. 'Favor alterar a situação de preenchimento, iniciando a coleta por ele. '
					. 'Após esta ação você terá acesso a todos os formulários, ao finalizar preenchimento e ao relatório de inconsistências.');
				$this->redirect(['/drenagem/consulta-prestadores/index?VisConsultaPrestadoresSearch[consulta]=&VisConsultaPrestadoresSearch[nome_municipio]=' . $nome_municipio . '']);
			} else {
				$this->session->setAlert([
					'type'  => 'warning',
					'title' => 'Atenção',
					'html'  => 'No momento os <b>formulários</b> estão <span class="text-red">bloqueados</span> para preenchimento.<br/><br/>'
						. 'Previsão de acesso aos <b>formulários</b>: Acompanhe o Cronograma do Componente Águas Pluviais para a próxima coleta de dados pelo link <a href="http://snis.gov.br" target="_blank">http://snis.gov.br</a>.<br/><br/>'
						. 'Agora você deve preencher e atualizar somente o <b>Cadastro Municipal</b> (<i>Prefeitura, Gestor do Serviço e Outras Entidades</i>).',
				]);
				
                $this->redirect(['/drenagem/inicio']);
			}
            
            return false;
        } elseif ($this->situacaoPreenchimento['cod_situacao_preenchimento'] == TabAtributosValoresSearch::CANCELADO) {
            $this->session->setFlash('warning', 'Este prestador está com a situação de preechimento "Cancelado". Portanto você não terá acesso aos formulários do mesmo.');
            $this->redirect(['/drenagem/consulta-prestadores/index?VisConsultaPrestadoresSearch[consulta]=&VisConsultaPrestadoresSearch[nome_municipio]=' . $nome_municipio . '']);
            
            return false;
        }
        
		return true;
	}
    
	public function podeSalvarDadosForm($msgSessao=true)
	{
		if ($this->isPost && $this->isFormHabilitado()) {
			if ($msgSessao) {
				$this->session->setFlash('danger', 'A situação de preenchimento não permite alterar os dados deste formulário.');
			}
			return false;
		}
		return true;
	}
	
	/**
	 * metodo que retorna os formularios habilitadas onde a situacao de preenchimento seja nao iniciado
	 * @return Array
	 */
	public function getFormulariosHabilitados() {
		$arrForm = [
            'gestao-municipal',
            'gestor-servico',
            'outras-entidades'
		];
        
		return $arrForm;
	}
    
	public function checkFormSendoUtilizadoPorOutroUsr(&$model)
	{
				
		$codUsuario = $this->app->user->identity->cod_usuario;
		
		static::limparSelecaoFormularios($codUsuario);
		
		switch ($model->className()) {
			case 'app\modules\drenagem\models\OutrasEntidades': 
				$campoCodUsuario = 'cod_usuario_oe_fk';
				break;
			case 'app\modules\drenagem\models\TabGestorServico': 
				$campoCodUsuario = 'cod_usuario_gs_fk';
				break;
			default:
				$campoCodUsuario = 'cod_usuario_fk';
		}
		
		if (null === $model->{$campoCodUsuario}) {
			$model->{$campoCodUsuario} = $codUsuario;
			$model->save(false);
			$model->refresh();
			$this->isFormSendoUtilizadoPorOutroUsr = false;
		}
		else {
			$session = \Yii::$app->session;
			$timeout = time() + $session->getTimeout();
			$timeout2 = time();
			
			$delete = "DELETE FROM  acesso.tab_sessao_php WHERE user_id={$model->{$campoCodUsuario}} AND expire <= {$timeout2};";
			$this->db->createCommand($delete)->execute();
			
			$userSession = \app\modules\admin\models\TabSessaoPhp::find()
				->where(['user_id' => $model->{$campoCodUsuario}])
				->andWhere(['<=', 'expire', $timeout])
				->one()
			;

			if (null === $userSession) {
				$model->{$campoCodUsuario} = $codUsuario;
				$model->save(false);
				$model->refresh();
				$this->isFormSendoUtilizadoPorOutroUsr = false;
			}
			elseif ($userSession->user_id != $codUsuario) {
				$user = \app\modules\admin\models\TabUsuarios::findOneAsArray(['cod_usuario' => $userSession->user_id]);
				$acessoForms = ['nome' => $user['txt_nome'], 'login' => $user['txt_login'], 'cod_usuario' => $user['cod_usuario']];
				$snis = (null === $user['cod_prestador_fk'])
					? '- Analista do SNIS'
					: ''
				;
				$msg = "
					Este formulário está sendo utilizado por outro usuário no momento. 
					Você pode visualizar os dados porém não pode modificá-los.
					<br> <b>Usuário ativo</b> {$acessoForms['nome']} {$snis}
				";
				$session->setFlash('warning', $msg);
				if (!$this->app->session->hasAlert()) {
					$session->setAlert([
						'type' => 'info',
						'title' => 'Atenção',
						'html' => $msg,
					]);
				}
				$this->isFormSendoUtilizadoPorOutroUsr = true;
			}
		}
	}
	
    public function isFormHabilitado()
	{
		if ($this->isFormSendoUtilizadoPorOutroUsr) {
			return true;
		}
		
        $form = $this->session->get('Formularios');
        
        // usuario prestador
        if ($this->module->getInfo()['usuario-perfil']['cod_prestador_fk'] != null) {
			if (
                (
					isset($form['ano_ref']) && 
					$form['ano_ref'] == $this->app->params['ano-ref'] && 
					in_array($this->situacaoPreenchimento['cod_situacao_preenchimento'], TabAtributosValoresSearch::getSituacoesHabilitadasPrestador())
				) ||
				(in_array(Yii::$app->controller->id, $this->getFormulariosHabilitados()))
            ) {
                return false;
            }
        }
        // usuario administrativo
        else {
            if ( ! $this->module->getInfo()['usuario-perfil']['cod_prestador_fk'] || 
				(
                isset($form['ano_ref']) && 
                $form['ano_ref'] == $this->app->params['ano-ref'] && 
                (
                    in_array($this->situacaoPreenchimento['cod_situacao_preenchimento'], TabAtributosValoresSearch::getSituacoesHabilitadasAdministrativo()) || 
                    (in_array(Yii::$app->controller->id, $this->getFormulariosHabilitados()))
                )
				)
            ) {
                return false;
            }
        }
        
        return true;
	}
	
	public function beforeAction($action) 
	{
		if (parent::beforeAction($action)) {

			// usuário prestador acessando
			if (isset($this->module->getInfo()['usuario-perfil']['cod_prestador_fk'])) {
				$table = '\app\modules\\' . $this->module->getInfo()['usuario-perfil']['modulo_id'] . '\models\VisParticipacoes';
				$p = TabUsuariosSearch::find()
					->select($table::tableName() . '.cod_participacao, ' . $table::tableName() . '.ano_ref, ' . $table::tableName() . '.cod_situacao_preenchimento_fk, ' . $table::tableName() . '.dsc_situacao_preenchimento')
					->innerJoin(RlcModulosPrestadoresSearch::tableName(), RlcModulosPrestadoresSearch::tableName() . '.cod_prestador_fk = ' . TabUsuariosSearch::tableName() . '.cod_prestador_fk')
					->innerJoin($table::tableName(), $table::tableName() . '.cod_modulo_prestador_fk = ' . RlcModulosPrestadoresSearch::tableName() . '.cod_modulo_prestador')
					->where(['=', TabUsuariosSearch::tableName() . '.cod_prestador_fk', $this->module->getInfo()['usuario-perfil']['cod_prestador_fk']])
					->andWhere(['=', RlcModulosPrestadoresSearch::tableName() . '.cod_modulo_fk', $this->module->getInfo()['cod_modulo']])
					->andWhere(['=', $table::tableName() . '.ano_ref', $this->app->params['ano-ref']])
					->asArray()
					->one();

				$params['cod_prestador'] = $this->module->getInfo()['usuario-perfil']['cod_prestador_fk'];
				$params['cod_participacao'] = $p['cod_participacao'];
				$params['situacao_preenchimento']['cod_situacao_preenchimento'] = $p['cod_situacao_preenchimento_fk'];
				$params['situacao_preenchimento']['dsc_situacao_preenchimento'] = $p['dsc_situacao_preenchimento'];

				$search = TabPrestadoresSearch::find()->where(['cod_prestador' => $params['cod_prestador']])->one();
				$participacao = $search->attributes;
				$participacao['localizacao'] = $search->sgl_estado_fk;

				$this->session->set('TabParticipacoes', array_merge($params, $participacao));
				$this->session->set('Formularios', ['cod_participacao' => $params['cod_participacao'], 'ano_ref' => $p['ano_ref']]);

				// @see self::actionSelecionaAnoRef
				if ($this->session->get('anoRefSelecionado') !== null) {
					$this->session->set('TabParticipacoes', $this->session->get('anoRefSelecionado')['tabParticipacoes']);
					$this->session->set('Formularios', $this->session->get('anoRefSelecionado')['formularios']);
				}
			}

			// usuário interno ou usuário prestador acessando após a sessão ser carregada
			if (($prestador = $this->session->get('TabParticipacoes'))) {
				// seta a sessão Formularios
				if ($this->session->get('Formularios') == null) {
					$this->session->set('Formularios', ['cod_participacao' => $prestador['cod_participacao'], 'ano_ref' => $this->app->params['ano-ref']]);
				}
				$this->where = [
					'participacao_fk' => $this->session->get('Formularios')['cod_participacao'],
					'num_ano_ref' => $this->session->get('Formularios')['ano_ref'],
				];
			} else {
				
				if ($this->pularVerificacaoPsvSelecionado) {
					Url::remember('', 'ultima-url-antes-de-acessar-consiulta-prestadores');
					$this->session->setFlash('warning', 'Selecione um prestador para ter acesso a esta funcionalidade.');
					$this->redirect(['/drenagem/consulta-prestadores']);
					return false;
				}
			}

			// seleciona a situação de preenchimento do prestador da coleta em questão
			$this->situacaoPreenchimento = $prestador['situacao_preenchimento'];

			// verifica situação de preenchimento do prestador
			// primeiro acesso: muda a situação de preenchimento de '0 - Não iniciado' para '1 - Sendo realizado pelo prestador'
			
			$md = \app\modules\admin\models\TabModulos::findOne(['id' => Yii::$app->controller->module->id]);
            $ic = explode('/', \app\models\TabParametrosSearch::findOne(['num_ano_ref' => Yii::$app->params['ano-ref'], 'sgl_parametro' => 'data-inicio-coleta', 'modulo_fk' => $md->cod_modulo])->vlr_parametro);
            $ic = $ic[2].'-'.$ic[1].'-'.$ic[0];
            $fc = explode('/', \app\models\TabParametrosSearch::findOne(['num_ano_ref' => Yii::$app->params['ano-ref'], 'sgl_parametro' => 'data-fim-coleta', 'modulo_fk' => $md->cod_modulo])->vlr_parametro);
            $fc = $fc[2].'-'.$fc[1].'-'.$fc[0];
            $dt = date('Y-m-d');
			
			if (isset($prestador) && $this->module->getInfo()['usuario-perfil']['cod_prestador_fk'] != null) {
				$p = TabParticipacoes::find()->where([
						'cod_participacao' => $this->where['participacao_fk'],
						'ano_ref' => $this->where['num_ano_ref'],
					])->one();
				
				if ($dt >= $ic && $dt <= $fc) {
					if ($p->cod_situacao_preenchimento_fk == TabAtributosValoresSearch::NAO_INICIADO) {
						$p->cod_situacao_preenchimento_fk = TabAtributosValoresSearch::SENDO_REALIZADO_PELO_PRESTADOR;
						$p->save();
					}
					
					$this->inserirRegistrosFormularios($this->where);
				} else {
					if ($p->cod_situacao_preenchimento_fk == TabAtributosValoresSearch::NAO_INICIADO && $p->dte_inicio_atualizacao_gm == null) {
						$p->dte_inicio_atualizacao_gm = 'now()';
						$p->save();
					}
				}
			}

			return true;
		}

		return false;
	}

	public function calcularPercentual($model) 
	{
		$obrigatorios = VisGlossarios::find()
				->select('lower(sgl_cod_info)')
				->where([
					'num_ano_ref' => $this->app->params['ano-ref'],
					'sgl_fam_info' => 'CB',
					'bln_obrigatorio' => true,
				])->column()
		;

		$campos = $model->getCamposCalculaveis();

		$total = count($campos);
		$totalPreenchidos = 0;

		foreach ($campos as $campo => $valor) {
			if (!empty($valor)) {
				$totalPreenchidos++;
			}
		}

		$preenchido = round($totalPreenchidos * 100 / $total, 2);
		$minimo = round(count($obrigatorios) * 100 / $total, 2);

		return [
			'minimo' => $minimo,
			'preenchido' => $preenchido,
		];
	}

	public function actionSelecionarAnoRef() 
	{
		$this->session->set('Formularios', [
			'cod_participacao' => $this->request->post('cod_participacao'),
			'ano_ref' => $this->request->post('ano_ref')
		]);

		$situacao_preenchimento = VisConsultaPrestadoresSearch::find()
			->where(['cod_participacao' => $this->request->post('cod_participacao')])
			->one();

		$arr = $this->session->get('TabParticipacoes');
		$arr['cod_participacao'] = $this->request->post('cod_participacao');
		$arr['situacao_preenchimento']['cod_situacao_preenchimento'] = $situacao_preenchimento['cod_situacao_preenchimento'];
		$arr['situacao_preenchimento']['dsc_situacao_preenchimento'] = $situacao_preenchimento['dsc_situacao_preenchimento'];
		$this->session->set('TabParticipacoes', $arr);

		$this->session->set('anoRefSelecionado', [
			'tabParticipacoes' => $arr,
			'formularios' => [
				'cod_participacao' => $this->request->post('cod_participacao'),
				'ano_ref' => $this->request->post('ano_ref')
			],
		]);

		$this->session->setFlash('success', "Ano de Referência <b>{$this->request->post('ano_ref')}</b> selecionado com sucesso.");

		return $this->json([
			'redirect' => Url::to(['/' . Yii::$app->controller->module->id . '/' . Yii::$app->controller->id])
		]);
	}

	public function actionPrint() 
	{
		$infoModulo = $this->module->info;
		$date = date('d-m-Y-H-i');
		$filename = "{$this->app->params['ano-ref']}-" . substr(str_replace('/', '-', $infoModulo['menu-item']['txt_url']), 1) . "-{$date}.pdf";
		$params = [
			'titulo' => $infoModulo['dsc_modulo'],
			'subtitulo' => "{$this->session->get('TabParticipacoes')['dg002']} - {$this->session->get('TabParticipacoes')['localizacao']['txt_nome']}/{$this->session->get('TabParticipacoes')['localizacao']['sgl_estado_fk']}",
			'subtitulo2' => $infoModulo['menu-item']['dsc_menu'],
			'ano' => $this->where['num_ano_ref'],
			'view' => $this->view,
			'destination' => (YII_ENV == 'dev' ? Pdf::DEST_BROWSER : Pdf::DEST_DOWNLOAD),
			'filename' => $filename,
			'content' => $this->actionView(),
		];

		return (new \projeto\pdf\PrintForm($params))->render();
	}

	public function geraModals(&$model) 
	{

		$avisosErros = [];

		if ($model->alertas['avisos']) {

			$model->modals['avisos'] = null;
			foreach ($model->alertas['avisos'] as $key => $value) {
				$html = \projeto\web\ActiveForm::getTabelaAvisosErros($value, 'Aviso');

				$model->modals['avisos'] .= $html;
				foreach ($value['sgl_cod_info'] as $sgl_cod_info) {
					$avisosErros[$sgl_cod_info]['A'][] = $html;
				}
			}
		}

		if ($model->alertas['erros']) {

			$model->modals['erros'] = null;
			foreach ($model->alertas['erros'] as $key => $value) {

				if ($key == 'E000') {
					foreach ($value as $key2 => $value2) {
						$html = \projeto\web\ActiveForm::getTabelaAvisosErros($value2, 'Erro');

						$model->modals['erros'] .= $html;
						//$model->modals['erros'] .= $html;

						foreach ($value2['sgl_cod_info'] as $sgl_cod_info) {
							$avisosErros[$sgl_cod_info]['E'][] = $html;
						}
					}
				} else {
					$html = \projeto\web\ActiveForm::getTabelaAvisosErros($value, 'Erro');

					$model->modals['erros'] .= $html;
					foreach ($value['sgl_cod_info'] as $sgl_cod_info) {
						$avisosErros[$sgl_cod_info]['E'][] = $html;
					}
				}
			}
		}

		$model->modals['geral'] = null;

		foreach ($avisosErros as $key => $value) {
            
			$html = "<table class=\"table table-striped\">
                <tr>
                    <td><b>Tipo</b></td>
                    <td><b>Código</b></td>
                    <td><b>Campos vinculados</b></td>
                    <td><b>Descrição</b></td>
                    <td><b>Fórmula</b></td>
                </tr>";
            
			foreach ($value as $key2 => $value2) {

				$modalId = 'modal-campo-' . strtolower($key);
				if (count($value) > 1) {
					$tituloModal = "Avisos e Erros do campo " . strtoupper($key);
				} else if ($key2 == 'A') {
					$tituloModal = "Avisos do campo " . strtoupper($key);
				} else {
					$tituloModal = "Erros do campo " . strtoupper($key);
				}

				foreach ($value2 as $key3 => $value3) {
					$html .= $value3;
				}
			}
            
            $html .= "</table>";
            
			$model->modals['geral'] .= $this->renderAjax('@app/modules/drenagem/views/modal-ae', ['modalId' => $modalId, 'titulo' => $tituloModal, 'html' => $html]);
		}
	}

	public function geraModalsAssoc(&$model) 
	{
		$avisosErros = [];

		if ($model->alertas['avisos']) {

			foreach ($model->alertas['avisos'] as $key => $value) {
				$html = \projeto\web\ActiveForm::getTabelaAvisosErros($value, 'Aviso');

				foreach ($value['sgl_cod_info'] as $sgl_cod_info) {
					$avisosErros[$sgl_cod_info]['A'][] = $html;
				}
			}
		}

		if ($model->alertas['erros']) {
			//$model->modals['erros'] = null;
			foreach ($model->alertas['erros'] as $key => $value) {

				if ($key == 'E000') {
					foreach ($value as $key2 => $value2) {
						$html = \projeto\web\ActiveForm::getTabelaAvisosErros($value2, 'Erro');
						foreach ($value2['sgl_cod_info'] as $sgl_cod_info) {
							$avisosErros[$sgl_cod_info]['E'][] = $html;
						}
					}
				} else {
					$html = \projeto\web\ActiveForm::getTabelaAvisosErros($value, 'Erro');
					foreach ($value['sgl_cod_info'] as $sgl_cod_info) {
						$avisosErros[$sgl_cod_info]['E'][] = $html;
					}
				}
			}
		}

		$model->modals['geral'] = null;

		foreach ($avisosErros as $key => $value) {

			$html = "<table class=\"table table-striped\">
                <tr>
                    <td><b>Tipo</b></td>
                    <td><b>Código</b></td>
                    <td><b>Campos vinculados</b></td>
                    <td><b>Descrição</b></td>
                    <td><b>Fórmula</b></td>
                </tr>";
			
			foreach ($value as $key2 => $value2) {

				$modalId = 'modal-campo-' . strtolower($key);
				if (count($value) > 1) {
					$tituloModal = "Avisos e Erros do campo " . strtoupper($key);
				} else if ($key2 == 'A') {
					$tituloModal = "Avisos do campo " . strtoupper($key);
				} else {
					$tituloModal = "Erros do campo " . strtoupper($key);
				}

				foreach ($value2 as $key3 => $value3) {
					$html .= $value3;
				}
			}
			$html .= "</table>";
			$model->modals['geral'] .= $this->renderAjax('@app/modules/drenagem/views/modal-ae', ['modalId' => $modalId, 'titulo' => $tituloModal, 'html' => $html]);
		}
	}
    
	public static function inserirRegistrosFormularios($params)
	{
        // insere registros em todos os formularios do prestador
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // TabGerais
            if (null === ($model_GE = \app\modules\drenagem\models\TabGerais::find()->where($params)->one())) {
                $dados_municipio = TabParticipacoesSearch::find()
                    ->select(TabMunicipiosSearch::tableName() . '.area_km2, ' . TabMunicipiosPopulacoes::tableName() . '.pop_tot, ' . TabMunicipiosPopulacoes::tableName() . '.pop_urb, ' . TabMunicipiosSearch::tableName() . '.regiao_hidrografica_fk, ' . TabAtributosValoresSearch::tableName() . '.sgl_valor')
                    ->innerJoin(RlcModulosPrestadoresSearch::tableName(), RlcModulosPrestadoresSearch::tableName() . '.cod_modulo_prestador = ' . TabParticipacoesSearch::tableName() . '.cod_modulo_prestador_fk')
                    ->innerJoin(TabPrestadoresSearch::tableName(), TabPrestadoresSearch::tableName() . '.cod_prestador = ' . RlcModulosPrestadoresSearch::tableName() . '.cod_prestador_fk')
                    ->innerJoin(TabMunicipiosSearch::tableName(), TabMunicipiosSearch::tableName() . '.cod_municipio = ' . TabPrestadoresSearch::tableName() . '.cod_municipio_fk')
                    ->innerJoin(TabMunicipiosPopulacoes::tableName(), TabMunicipiosPopulacoes::tableName() . '.municipio_fk = ' . TabMunicipiosSearch::tableName() . '.cod_municipio and ' . TabMunicipiosPopulacoes::tableName() . '.ano_ref = :ano_ref', [':ano_ref' => \Yii::$app->params['ano-ref']])
                    ->innerJoin(TabAtributosValoresSearch::tableName(), TabAtributosValoresSearch::tableName() . '.cod_atributos_valores = ' . TabMunicipiosSearch::tableName() . '.regiao_hidrografica_fk')
                    ->where(['=', TabParticipacoesSearch::tableName() . '.cod_participacao', $params['participacao_fk']])
                    ->andWhere(RlcModulosPrestadoresSearch::tableName() . '.dte_exclusao is null')
                    ->andWhere(TabPrestadoresSearch::tableName() . '.dte_exclusao is null')
                    ->asArray()
                    ->one();

                $model_GE = new \app\modules\drenagem\models\TabGerais($params);
                $model_GE->ge001 = $dados_municipio['area_km2'];
                $model_GE->ge005 = $dados_municipio['pop_tot'];
                $model_GE->ge006 = $dados_municipio['pop_urb'];
                $model_GE->ge010 = $dados_municipio['regiao_hidrografica_fk'];

                $model_GE->save();
                
                // bacias hidrograficas
                $dados_bacias = TabParticipacoesSearch::find()
                    ->select(TabBaciasHidrograficas::tableName() . '.txt_nome')
                    ->innerJoin(RlcModulosPrestadoresSearch::tableName(), RlcModulosPrestadoresSearch::tableName() . '.cod_modulo_prestador = ' . TabParticipacoesSearch::tableName() . '.cod_modulo_prestador_fk')
                    ->innerJoin(TabPrestadoresSearch::tableName(), TabPrestadoresSearch::tableName() . '.cod_prestador = ' . RlcModulosPrestadoresSearch::tableName() . '.cod_prestador_fk')
                    ->innerJoin(TabMunicipiosSearch::tableName(), TabMunicipiosSearch::tableName() . '.cod_municipio = ' . TabPrestadoresSearch::tableName() . '.cod_municipio_fk')
                    ->innerJoin(RlcMunicipiosBaciasHidrograficas::tableName(), RlcMunicipiosBaciasHidrograficas::tableName() . '.municipio_fk = ' . TabMunicipiosSearch::tableName() . '.cod_municipio')
                    ->innerJoin(TabBaciasHidrograficas::tableName(), TabBaciasHidrograficas::tableName() . '.cod_bacia_hidrografica = ' . RlcMunicipiosBaciasHidrograficas::tableName() . '.bacia_hidrografica_fk')
                    ->where(['=', TabParticipacoesSearch::tableName() . '.cod_participacao', $params['participacao_fk']])
                    ->andWhere(RlcModulosPrestadoresSearch::tableName() . '.dte_exclusao is null')
                    ->andWhere(TabPrestadoresSearch::tableName() . '.dte_exclusao is null')
                    ->asArray()
                    ->all();
                foreach ($dados_bacias as $value) {
                    $model_GE_Bacias = new \app\modules\drenagem\models\TabGeraisBaciasHidrograficas();
                    $model_GE_Bacias->geral_fk = $model_GE->cod_geral;
                    $model_GE_Bacias->ge011 = $value['txt_nome'];
                    $model_GE_Bacias->save();
                }
            }

            // TabCobrancas
            if (null === ($model_CB = \app\modules\drenagem\models\TabCobrancas::find()->where($params)->one())) {
                $model_CB = new \app\modules\drenagem\models\TabCobrancas($params);
                $model_CB->save();
            }

            // TabFinanceiros
            if (null === ($model_FI = \app\modules\drenagem\models\TabFinanceiros::find()->where($params)->one())) {
                $model_FI = new \app\modules\drenagem\models\TabFinanceiros($params);
                $model_FI->save();
            }

            // TabInfraestruturas
            if (null === ($model_IE = \app\modules\drenagem\models\TabInfraestruturas::find()->where($params)->one())) {
                $model_IE = new \app\modules\drenagem\models\TabInfraestruturas($params);
                $model_IE->save();
            }

            // TabOperacionais
            if (null === ($model_OP = \app\modules\drenagem\models\TabOperacionais::find()->where($params)->one())) {
                $model_OP = new \app\modules\drenagem\models\TabOperacionais($params);
                $model_OP->save();
            }

            // TabRiscos
            if (null === ($model_RI = \app\modules\drenagem\models\TabRiscos::find()->where($params)->one())) {
                $dados_municipio = TabParticipacoesSearch::find()
                    ->select(TabMunicipiosDesastres::tableName() . '.*')
                    ->innerJoin(RlcModulosPrestadoresSearch::tableName(), RlcModulosPrestadoresSearch::tableName() . '.cod_modulo_prestador = ' . TabParticipacoesSearch::tableName() . '.cod_modulo_prestador_fk')
                    ->innerJoin(TabPrestadoresSearch::tableName(), TabPrestadoresSearch::tableName() . '.cod_prestador = ' . RlcModulosPrestadoresSearch::tableName() . '.cod_prestador_fk')
                    ->innerJoin(TabMunicipiosSearch::tableName(), TabMunicipiosSearch::tableName() . '.cod_municipio = ' . TabPrestadoresSearch::tableName() . '.cod_municipio_fk')
                    ->innerJoin(TabMunicipiosDesastres::tableName(), TabMunicipiosDesastres::tableName() . '.municipio_fk = ' . TabMunicipiosSearch::tableName() . '.cod_municipio')
                    ->where(TabMunicipiosDesastres::tableName() . '.ano_ref::integer >= (' . TabParticipacoesSearch::tableName() . '.ano_ref::integer - 5)')
                    ->andWhere(['=', TabParticipacoesSearch::tableName() . '.cod_participacao', $params['participacao_fk']])
                    ->andWhere(RlcModulosPrestadoresSearch::tableName() . '.dte_exclusao is null')
                    ->andWhere(TabPrestadoresSearch::tableName() . '.dte_exclusao is null')
                    ->asArray()
                    ->all();

                $ri022 = $ri023 = $ri024 = $ri025 = $ri026 = $ri027 = $ri028 = $ri029 = $ri030 = $ri031 = 0;
                foreach ($dados_municipio as $value) {
                    if ($value['txt_tipo_desastre'] == 'E') {
                        if ($params['num_ano_ref'] == $value['ano_ref']) {
                            $ri023 = $value['num_desastres'];
                        }
                        $ri022 = $ri022 + $value['num_desastres'];
                    }

                    if ($value['txt_tipo_desastre'] == 'A') {
                        if ($params['num_ano_ref'] == $value['ano_ref']) {
                            $ri025 = $value['num_desastres'];
                        }
                        $ri024 = $ri024 + $value['num_desastres'];
                    }

                    if ($value['txt_tipo_desastre'] == 'I') {
                        if ($params['num_ano_ref'] == $value['ano_ref']) {
                            $ri027 = $value['num_desastres'];
                        }
                        $ri026 = $ri026 + $value['num_desastres'];
                    }
                    
                    $ri028 = $ri028 + $value['num_desabrigados'] + $value['num_desalojados'];
                    $ri030 = $ri030 + $value['num_obitos'];
                    if ($params['num_ano_ref'] == $value['ano_ref']) {
                        $ri029 = $value['num_desabrigados'] + $value['num_desalojados'];
                        $ri031 = $value['num_obitos'];
                    }                    
                }

                $model_RI = new \app\modules\drenagem\models\TabRiscos($params);
                $model_RI->ri022 = $ri022;
                $model_RI->ri023 = $ri023;
                $model_RI->ri024 = $ri024;
                $model_RI->ri025 = $ri025;
                $model_RI->ri026 = $ri026;
                $model_RI->ri027 = $ri027;
                $model_RI->ri028 = $ri028;
                $model_RI->ri029 = $ri029;
                $model_RI->ri030 = $ri030;
                $model_RI->ri031 = $ri031;

                $model_RI->save();
            }

            // TabAvaliacoesReacoes
            if (null === ($model_AR = \app\modules\drenagem\models\TabAvaliacoesReacoes::find()->where($params)->one())) {
                $model_AR = new \app\modules\drenagem\models\TabAvaliacoesReacoes($params);
                $model_AR->save();
            }

            $transaction->commit();
        } catch (Exception $e) {
            $this->session->setFlash('danger', $e->getMessage());
            $transaction->rollBack();
        }
	}
    
	public static function excluirRegistrosFormularios($params)
	{
        // excluir registros em todos os formularios do prestador
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // TabCampoMultiOpcoes
            if (null !== ($model_Campo_Multi_Opcoes = \app\modules\drenagem\models\TabCampoMultiOpcoes::find()->where(['participacao_fk' => $params['participacao_fk']])->asArray()->one())) {
                \app\modules\drenagem\models\TabCampoMultiOpcoes::deleteAll(['participacao_fk' => $params['participacao_fk']]);
            }
            
            // TabGerais
            if (null !== ($model_GE = \app\modules\drenagem\models\TabGerais::find()->where($params)->asArray()->one())) {
                if (null !== ($model_GE_Bacias = \app\modules\drenagem\models\TabGeraisBaciasHidrograficas::find()->where(['geral_fk' => $model_GE['cod_geral']])->asArray()->one())) {
                    \app\modules\drenagem\models\TabGeraisBaciasHidrograficas::deleteAll(['geral_fk' => $model_GE['cod_geral']]);
                }
                if (null !== ($model_GE_Rios = \app\modules\drenagem\models\TabGeraisRios::find()->where(['geral_fk' => $model_GE['cod_geral']])->asArray()->one())) {
                    \app\modules\drenagem\models\TabGeraisRios::deleteAll(['geral_fk' => $model_GE['cod_geral']]);
                }
                \app\modules\drenagem\models\TabGerais::deleteAll(['cod_geral' => $model_GE['cod_geral']]);
            }

            // TabCobrancas
            if (null !== ($model_CB = \app\modules\drenagem\models\TabCobrancas::find()->where($params)->asArray()->one())) {
                \app\modules\drenagem\models\TabCobrancas::deleteAll(['cod_cobranca' => $model_CB['cod_cobranca']]);
            }

            // TabFinanceiros
            if (null !== ($model_FI = \app\modules\drenagem\models\TabFinanceiros::find()->where($params)->asArray()->one())) {
                \app\modules\drenagem\models\TabFinanceiros::deleteAll(['cod_financeiro' => $model_FI['cod_financeiro']]);
            }

            // TabInfraestruturas
            if (null !== ($model_IE = \app\modules\drenagem\models\TabInfraestruturas::find()->where($params)->asArray()->one())) {
                if (null !== ($model_IE_Bacias = \app\modules\drenagem\models\TabInfraestruturasBacias::find()->where(['cod_infraestrutura_fk' => $model_IE['cod_infraestrutura']])->asArray()->one())) {
                    \app\modules\drenagem\models\TabInfraestruturasBacias::deleteAll(['cod_infraestrutura_fk' => $model_IE['cod_infraestrutura']]);
                }
                if (null !== ($model_IE_Outras_Areas = \app\modules\drenagem\models\TabInfraestruturasOutrasAreas::find()->where(['cod_infraestrutura_fk' => $model_IE['cod_infraestrutura']])->asArray()->one())) {
                    \app\modules\drenagem\models\TabInfraestruturasOutrasAreas::deleteAll(['cod_infraestrutura_fk' => $model_IE['cod_infraestrutura']]);
                }
                if (null !== ($model_IE_Parques = \app\modules\drenagem\models\TabInfraestruturasParques::find()->where(['cod_infraestrutura_fk' => $model_IE['cod_infraestrutura']])->asArray()->one())) {
                    \app\modules\drenagem\models\TabInfraestruturasParques::deleteAll(['cod_infraestrutura_fk' => $model_IE['cod_infraestrutura']]);
                }
                \app\modules\drenagem\models\TabInfraestruturas::deleteAll(['cod_infraestrutura' => $model_IE['cod_infraestrutura']]);
            }

            // TabOperacionais
            if (null !== ($model_OP = \app\modules\drenagem\models\TabOperacionais::find()->where($params)->asArray()->one())) {
                if (null !== ($model_OP_Intervencoes = \app\modules\drenagem\models\TabOperacionaisIntervencoes::find()->where(['cod_operacional_fk' => $model_OP['cod_operacional']])->asArray()->one())) {
                    \app\modules\drenagem\models\TabOperacionaisIntervencoes::deleteAll(['cod_operacional_fk' => $model_OP['cod_operacional']]);
                }
                \app\modules\drenagem\models\TabOperacionais::deleteAll(['cod_operacional' => $model_OP['cod_operacional']]);
            }

            // TabRiscos
            if (null !== ($model_RI = \app\modules\drenagem\models\TabRiscos::find()->where($params)->asArray()->one())) {
                \app\modules\drenagem\models\TabRiscos::deleteAll(['cod_risco' => $model_RI['cod_risco']]);
            }

            // TabAvaliacoesReacoes
            if (null !== ($model_AR = \app\modules\drenagem\models\TabAvaliacoesReacoes::find()->where($params)->asArray()->one())) {
                \app\modules\drenagem\models\TabAvaliacoesReacoes::deleteAll(['cod_avaliacao_reacao' => $model_AR['cod_avaliacao_reacao']]);
            }

            $transaction->commit();
        } catch (Exception $e) {
            $this->session->setFlash('danger', $e->getMessage());
            $transaction->rollBack();
        }
	}
    
    public function setInfoLog($model, $assoc = []) {
        $this->searchModelLog = new VwLogSearch();
        
        $params = [];
        if (Yii::$app->request->queryParams)
            $params = Yii::$app->request->queryParams;
        
        $params['table_name'] = (strpos($model->tableName(), '.') === false) ? 'public.' . $model->tableName() : $model->tableName();
        $params['participacao_fk'] = $this->where['participacao_fk'];
        
        $this->dataProviderLog = $this->searchModelLog->search($params, $assoc);
        $this->filtrosLog = $this->searchModelLog->filtrosParaPesquisa($params, $assoc);
        
        $this->paramsLog['params'] = $params;
        $this->paramsLog['assoc'] = $assoc;
    }
    
    /**
     * Metodo para imprimir o log do formulario
     */
    public function actionPrintLog() {
        if (Yii::$app->request->get('paramsLog')) {
            $ano_ref    = $this->app->params['ano-ref'];
            $modulo_id  = $this->module->id;
            $modulo_dsc = $this->module->info['dsc_modulo'];
            $formulario = $this->module->getInfo()['menu-item']['txt_nome'];
            $prestador  = $this->session->get('TabParticipacoes')['dg002'];
            $municipio  = $this->session->get('TabParticipacoes')['localizacao']['txt_nome'];
            $uf         = $this->session->get('TabParticipacoes')['localizacao']['sgl_estado_fk'];
			$data       = date('d-m-Y-H-i-s');
            
            $paramsLog      = Yii::$app->request->get('paramsLog');
            $searchModelLog = new VwLogSearch();
            $dados          = $searchModelLog->search($paramsLog['params'], ((isset($paramsLog['assoc'])) ? $paramsLog['assoc'] : null), 'isDataProvider');
            
			$params = [
				'view'        => $this->view,
				'destination' => (YII_ENV == 'dev' ? Pdf::DEST_BROWSER : Pdf::DEST_DOWNLOAD),
				'filename'    => "{$modulo_id}-relatorio-log-{$formulario}-{$municipio}-{$uf}-{$data}.pdf",
				'content'     => $this->renderPartial('/log-print', [
                    'params'  => $paramsLog['params'],
					'dados'   => $dados,
				]),
				'titulo'      => $modulo_dsc,
				'subtitulo'   => "Relatório de Log - {$formulario}",
				'subtitulo2'  => "{$prestador} / {$municipio} - {$uf}",
				'ano'         => $ano_ref,
                'cssInline' => '
                    table {
                        font-size: 10px;
                    }
                    td {
                        padding: 4px;
                    }
					.alert-success {
						padding: 2px;
					}
                ',
			];
			
			return (new \projeto\pdf\PrintView($params))->render();
		}
    }
    
    /**
     * metodo que valida o acesso a funcionalidade de analise automatica
     */
    public function validaAcessoAnaliseAutomatica()
	{
        if (($tabParticipacoes = $this->session->get('TabParticipacoes'))) {
            $nome_municipio = "{$tabParticipacoes['localizacao']['txt_nome']} - {$tabParticipacoes['localizacao']['sgl_estado_fk']}";
        }
        
        $msg = 'O acesso ao menu <b>"Análise automática"</b> está suspenso nesta etapa do processo.';
        
        // usuario prestador
        if ($this->module->getInfo()['usuario-perfil']['cod_prestador_fk'] != null) {
            if ($this->situacaoPreenchimento['cod_situacao_preenchimento'] != TabAtributosValoresSearch::EM_ANALISE_AUTOMATICA) {
                $this->session->setFlash('warning', $msg);
                $this->redirect(['/drenagem']);
                
                return false;
            }
        }
        // usuario administrativo
        else {
            if (
                $this->situacaoPreenchimento['cod_situacao_preenchimento'] != TabAtributosValoresSearch::EM_ANALISE_ANALISTA &&
                $this->situacaoPreenchimento['cod_situacao_preenchimento'] != TabAtributosValoresSearch::EM_ANALISE_AUTOMATICA
            ) {
                $this->session->setFlash('warning', $msg);
                $this->redirect(['/drenagem/consulta-prestadores/index?VisConsultaPrestadoresSearch[consulta]=&VisConsultaPrestadoresSearch[nome_municipio]=' . $nome_municipio . '']);
                
                return false;
            }
        }
        
        return true;
	}
	
	
	public static function limparSelecaoFormularios($usuario) {

		$forms = [
			'GE' => '\app\modules\drenagem\models\base\TabGerais',
			'CB' => '\app\modules\drenagem\models\base\TabCobrancas',
			'FN' => '\app\modules\drenagem\models\base\TabFinanceiros',
			'IE' => '\app\modules\drenagem\models\base\TabInfraestruturas',
			'OP' => '\app\modules\drenagem\models\base\TabOperacionais',
			'RI' => '\app\modules\drenagem\models\base\TabRiscos',
			'AR' => '\app\modules\drenagem\models\base\TabAvaliacoesReacoes',
			'GM' => '\app\models\TabPrestadores',
			//'GS' => '', 
			//'OE' => '\app\models\RlcModulosPrestadores',
		];
		
		foreach ($forms as $key => $value) {
			$form = new $value;
			foreach ($form->find()->where(['cod_usuario_fk' => $usuario])->all() as $key => $value) {
				
				if ($value->cod_usuario_fk) {
					$value->cod_usuario_fk = null;
					
					$value->save(false);
				}
			}
		}
		
		$gs_oe = \app\models\RlcModulosPrestadores::find()->where(" cod_usuario_gs_fk={$usuario} OR cod_usuario_oe_fk={$usuario} ")->all();

		if ($gs_oe) {
			foreach ($gs_oe as $key => $value) {
				if ($value->cod_usuario_oe_fk || $value->cod_usuario_oe_fk) {
					$value->cod_usuario_gs_fk = null;
					$value->cod_usuario_oe_fk = null;
					$value->save(false);
				}
			}
		}
	}
    
}
