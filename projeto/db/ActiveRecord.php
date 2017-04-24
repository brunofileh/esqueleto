<?php

namespace projeto\db;

use app\modules\drenagem\models\TabCampoMultiOpcoes;
use app\models\VisGlossarios;
use app\models\VisAtributosValores;
use projeto\Util;

class ActiveRecord extends \yii\db\ActiveRecord {

	use \projeto\Atalhos;

	// timestamps
	const AUDIT_CAMPO_INCLUSAO = 'dte_inclusao';
	const AUDIT_CAMPO_ALTERACAO = 'dte_alteracao';
	// usuário última alteração
	const AUDIT_CAMPO_USUARIO = 'txt_login_inclusao';

	// campos de multipla selecao
	protected $camposMultiSelecao = [];
	// estado inicial dos campos
	protected $camposMultiSelecaoEstadoInicial = [];
	// campos da tabela tab_atributos_valores 10@DN
	protected $camposChaveAttrVlr = [];
	public $alertas = [];
	public $modals = [];
	public $avisoErrosTotal = [];
	public $dependencias = [];
	public $dependenciasForm = [];
	public $associativas = [];
	public $percentual = [];

	const TIPO = '';
	const FORM = '';

	// Formulários que não devem considerar dados_salvos
	public $formularios_sem_dados_salvos = ['GM', 'GS', 'OE'];

	public function init() {

		parent::init();
		
		$this->configAtalhos();
		
	}

	public function afterFind() {
		if (count($this->camposMultiSelecao) > 0) {
			$this->selectMultiOpcoes($this->camposMultiSelecao);
		}
		if (count($this->camposChaveAttrVlr) > 0) {
			$this->carregarCamposChaveAttrVlr($this->camposChaveAttrVlr);
		}

		parent::afterFind();
	}

	public function carregarCamposChaveAttrVlr(array $arrCampos) {
		foreach ($arrCampos as $campo) {
			if (!empty($this->$campo)) {
				$row = VisAtributosValores::find()
					->where(['cod_atributos_valores' => $this->$campo])
					->asArray()
					->one()
				;
				$this->$campo = "{$row['cod_atributos_valores']}@{$row['sgl_valor']}";
			}
		}
	}

	public function selectMultiOpcoes(array $arrCodInfo) {
		if (!isset($this->app->controller->where['participacao_fk'])) {
			return;
		}

		foreach ($arrCodInfo as $codInfo) {
			$id = $this->$codInfo;
			$opcoes = TabCampoMultiOpcoes::selecaoAttrValor($this->app->controller->where['participacao_fk'], static::tableName(), $codInfo);
			$this->$codInfo = $opcoes;

			$this->camposMultiSelecaoEstadoInicial[$codInfo] = [
				'id' => $id,
				'opt' => $opcoes,
			];
		}
	}

	public function save($runValidation = true, $attributeNames = null) {

		if (isset($this->dados_salvos)) {
			$this->dados_salvos = 1;
		}

		if (count($this->camposMultiSelecao) > 0 && !$this->isNewRecord) {
			/**
			 * Prepara para salvar
			 */
			$vlrCamposMulti = [];
			$vlrCamposMultiDeletar = [];

			foreach ($this->camposMultiSelecao as $codInfo) {
				// yii2 envia '' ao invés de [] quando não há seleção
				if ($this->$codInfo == '') {
					$this->$codInfo = [];
				}

				// salva somente se mudar o estado/opções selecionadas do campo
				if ($this->$codInfo != $this->camposMultiSelecaoEstadoInicial[$codInfo]['opt']) {
					$vlrCamposMultiDeletar[] = $this->camposMultiSelecaoEstadoInicial[$codInfo]['id'];
					$vlrCamposMulti[$codInfo] = $this->$codInfo;
				}
				// salva a sequencia na tabela de origem
				$this->$codInfo = $this->camposMultiSelecaoEstadoInicial[$codInfo]['id'];
			}

			try {
				$transaction = $this->db->beginTransaction();
				// deleta os campos relacionados caso tenha havido alguma modificação
				if (count($vlrCamposMultiDeletar) > 0) {
					TabCampoMultiOpcoes::deleteAll([
						'cod_sequencia_tabela_pai' => $vlrCamposMultiDeletar
					]);
				}

				// salva os dados na tabela primária
				$r = parent::save($runValidation, $attributeNames);

				// salva os campos relacionados
				foreach ($vlrCamposMulti as $codInfo => $linha) {

					foreach ($linha as $seq) {
						(new TabCampoMultiOpcoes([
						'atributos_valores_fk' => \projeto\Util::attrId($seq),
						'cod_sequencia_tabela_pai' => $this->$codInfo,
						'dsc_tabela' => static::tableName(),
						'sgl_cod_info' => $codInfo,
						'num_ano_ref' => $this->app->params['ano-ref'],
						'participacao_fk' => $this->participacao_fk,
						]))->save();
					}
					// volta o valor da sequencia para o campo
					$this->$codInfo = $linha;
					// atualiza o estado do campo (seleção inicial)
					$this->camposMultiSelecaoEstadoInicial[$codInfo]['opt'] = $linha;
				}

				// comita a transação
				$transaction->commit();
				return $r;
			} catch (\Exception $e) {
				$transaction->rollBack();
				throw $e;
			}
		} else {
			return parent::save($runValidation, $attributeNames);
		}
	}

	public function metaInfo() {
		throw new \yii\base\NotSupportedException('Implemente o método metaInfo() na model ' . $this::className());
	}

	public function glossario($codInfo = null) {
		
		$getData = function ($codInfo = null) {
			$metaInfo = $this->metaInfo();
			if($codInfo){
				$metaInfo['sgl_cod_info'] =  strtoupper($codInfo);
			}
			
			return VisGlossarios::find()
					->where($metaInfo)
					->indexBy('sgl_cod_info')
					->asArray()
					->all()
			;
		};

		if (\Yii::$app->params['habilitar-cache-global']) {
			$cacheKey = ['glossario'];
			if (!is_null($codInfo)) {
				$cacheKey[] = $codInfo;
			}
			if (($dados = \Yii::$app->cache->get($cacheKey)) === false) {
				$dados = $getData();
				\Yii::$app->cache->set($cacheKey, $dados);
			}
		} else {
			if($codInfo){
				$dados = $getData($codInfo);
			}else{
				$dados = $getData();
			}
		}

		if ($codInfo) {
			$codInfo = strtoupper($codInfo);
			if (isset($dados[$codInfo])) {
				return $dados[$codInfo];
			} else {
				$erro = "Código da informação não encontrado glossário: $codInfo";
				throw new \yii\db\Exception($erro);
			}
		} else {
			return $dados;
		}
	}

	public function beforeSave($insert) {
		if (parent::beforeSave($insert)) {
			/**
			 * BEFORE-SAVE
			 * Verifica a existencia dos campos de auditoria na model.
			 * Caso exista algum então seta os valores antes de salvar.
			 */
			$attributeLabels = $this->attributeLabels();
			$campoInclusao = self::AUDIT_CAMPO_INCLUSAO;
			$campoAlteracao = self::AUDIT_CAMPO_ALTERACAO;
			$campoUsuario = self::AUDIT_CAMPO_USUARIO;

			// insere "dt_inclusao" quando a operação for insert
			if ($this->isNewRecord && isset($attributeLabels[$campoInclusao])) {
				$this->{$campoInclusao} = 'NOW()';
			}
			// insere "dt_alteracao" sempre
			if (isset($attributeLabels[$campoAlteracao])) {
				$this->{$campoAlteracao} = 'NOW()';
			}
			// quem fez a ultima modificação? 
			if (isset($attributeLabels[$campoUsuario]) && !\Yii::$app->user->isGuest) {
				$this->{$campoUsuario} = \Yii::$app->user->identity->txt_login;
			}

			// Converte os campos da tabela atributo_valor para poder salvar apenas o ID
			foreach ($this->camposChaveAttrVlr as $campo) {
				$this->$campo = Util::attrId($this->$campo);
			}
			/**
			 * END - BEFORE-VALIDADE
			 */
			return true;
		} else {
			return false;
		}
	}

	public static function findOneAsArray(array $where = []) {
		return static::find()->where($where)->asArray()->one();
	}

	public static function findAllAsArray(array $where = []) {
		return static::find()->where($where)->orderBy('')->asArray()->all();
	}

	/**
	 * Carrega a descrição das chaves estrangeiras 
	 * da tabela de atributos valores dentro da model em questão
	 *
	 * static::carregarDescricaoAtributoValor(['atributo_fk' => 'dsc_atributo'])
	 */
	public function carregarDescricaoAtributoValor(array $pares) {
		foreach ($pares as $attrFk => $attrDsc) {
			$_attrFk = strpos($this->$attrFk, '@') !== false ? \projeto\Util::attrId($this->$attrFk) : $this->$attrFk
			;
			$r = VisAtributosValores::find()
				->where(['cod_atributos_valores' => $_attrFk])
				->asArray()
				->one()
			;
			$this->$attrDsc = $r['dsc_descricao'];
		}
	}

	public function getCamposCalculaveis() {
		
	}

	public function calculaTotalAvisosErros() {
		$this->avisoErrosTotal['avisos'] = 0;
		$this->avisoErrosTotal['erros'] = 0;
		if (!$this->isNewRecord) {
			$this->avisoErrosTotal['erros'] += $this->classeErros->getAlertasTotal();
			$this->avisoErrosTotal['avisos'] += $this->classeAvisos->getAlertasTotal();
			
			if ($this->associativas) {
				foreach ($this->associativas as $assoc) {
					foreach ($this->$assoc as $assocClass) {

						$assocClass->calculaTotalAvisosErros();
						$this->avisoErrosTotal['avisos'] += $assocClass->avisoErrosTotal['avisos'];
						$this->avisoErrosTotal['erros'] += $assocClass->avisoErrosTotal['erros'];
					}
				}
			}
		}
		return $this->avisoErrosTotal;
	}

	public function calculaAvisosErros() {
		$this->alertas['avisos'] = null;
		$this->alertas['erros'] = null;
		if (!$this->isNewRecord) {
			$this->alertas['erros'] = $this->classeErros->calcular();
			$this->alertas['avisos'] = $this->classeAvisos->calcular();
			
		}

		if ($this->associativas) {
			$this->alertas['avisos'] = array_merge($this->alertas['avisos'], $this->classeAvisos->calcularAssoc($this->associativas, 'classeAvisos'));
			$this->alertas['erros'] = array_merge($this->alertas['erros'], $this->classeErros->calcularAssoc($this->associativas, 'classeErros'));
		}

	}

	public function gerarTodosCalculos() {
		$this->avisoErrosTotal['avisos'] = 0;
		$this->avisoErrosTotal['erros'] = 0;

		$this->alertas['avisos'] = null;
		$this->alertas['erros'] = null;

		if ((isset($this->dados_salvos) && $this->dados_salvos == 1) || in_array(static::FORM, $this->formularios_sem_dados_salvos)) {
			$this->calculaTotalAvisosErros();
			$this->calculaAvisosErros();
		}
		$this->calcularDependencias();
		$this->calcularPercentual();
	}

	public function calcularPercentual() {
		$obrigatorios = VisGlossarios::find()
				->select('lower(sgl_cod_info)')
				->where([
					'num_ano_ref' => $this->app->params['ano-ref'],
					'sgl_form' => static::FORM,
					'bln_obrigatorio' => true,
				])->column()
		;

		$campos = $this->getCamposCalculaveis();

		$total = count($campos);

		$totalPreenchidos = 0;

		if ($campos) {
			foreach ($campos as $campo => $valor) {
				if (!empty($valor)) {
					$totalPreenchidos++;
				}
			}
		}


		$preenchido = ($total > 0) ? round($totalPreenchidos * 100 / $total, 2) : 0;
		$minimo = ($total > 0) ? round(count($obrigatorios) * 100 / $total, 2) : 0;

		$this->percentual = [
			'minimo' => $minimo,
			'preenchido' => $preenchido,
		];

		return $this->percentual;
	}

	public function calcularDependencias() {
		$class = get_class($this);

		$form = \app\models\TabForm::find()->where(['sgl_form' => static::FORM])->one();

		//$campos = \app\models\TabGlossariosSearch::findAllAsArray(['fk_glossario_form' => $form->cod_form, 'bln_info_ativa' => true]);
		$campos = \app\models\TabGlossariosSearch::find()->select(' sgl_cod_info, cod_glossario,'
			. '(SELECT count(sgl_cod_info) FROM dicionario.tab_glossarios as gg where dicionario.tab_glossarios.cod_glossario=gg.dependencia_glossario_fk )  as dependencia'
			. ' ')->where(['fk_glossario_form' => $form->cod_form, 'bln_info_ativa' => true])->asArray()->all();
		$filhos = [];

		foreach ($campos as $campo) {

			$filhos[$campo['sgl_cod_info']] = [];
			$class::montarDependencia($campo['cod_glossario'], $filhos[$campo['sgl_cod_info']], $campo['dependencia']);

			if (!$filhos[$campo['sgl_cod_info']]) {
				unset($filhos[$campo['sgl_cod_info']]);
			}
		}	
		
		$this->dependencias = $filhos;
	}

	public function montarDependencia($cod_glossario = null, &$filhos = [], $dependencia = 0) {
		$class = get_class($this);
		
		if($dependencia>0){
			$deps = \app\models\VisGlossarios::findAllAsArray(['dependencia_glossario_fk' => $cod_glossario, 'bln_info_ativa' => true]);

			if ($deps) {

				foreach ($deps as $key => $dep) {

					$this->dependenciasForm[$dep['sgl_form']] = $dep['dsc_glossario_form'];
					$filhos[$dep['sgl_form']][] = $dep['sgl_cod_info'];

					$depsFilho = $class::montarDependencia($dep['cod_glossario'], $filhos, 1);
				}
			}
		}
	}

	public function montarDependenciaText($json = false) {

		if ($this->dependencias) {

			foreach ($this->dependencias as $key => $depCamp) {
				$dependenciasTxtF = $dependenciasTxtF = [];
				foreach ($depCamp as $keyF => $depCampF) {
					//$form = \app\models\TabForm::find()->select('dsc_form')->where(['sgl_form' => $keyF])->one()->dsc_form;
					$form = $this->dependenciasForm[$keyF];
					
					if ($keyF == static::FORM) {
						$dependenciasTxt[strtolower($key)] = ($json) ? "<b>" . $form . ": </b>" . implode(', ', $depCampF) . '.' : implode(', ', $depCampF);
					} else {

						$dependenciasTxtF[strtolower($key)] = ($json) ? "<br /><b>" . $form . ": </b>" . implode("; ", \app\models\TabGlossariosSearch::getSglDscInformacao($depCampF)) . "." : implode(', ', $depCampF);
					}
				}
				if (isset($dependenciasTxtF[strtolower($key)])) {

					$dependenciasTxt[strtolower($key)] = $dependenciasTxt[strtolower($key)] . $dependenciasTxtF[strtolower($key)];
				}
			}
			return ($json) ? json_encode($dependenciasTxt) : $dependenciasTxt;
		}

		return ($json) ? json_encode([]) : [];
	}

	/**
	 * E000
	 * @return boolean
	 */
	public function E000($campo)
	{

        if (array_key_exists($campo, $this->attributes) && $this->$campo == '' ) {
            return true;
        }
        	
		return false;
	}
}
