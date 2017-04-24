<?php

/**
 * Trait usada na criação de avisos / erros
 */

namespace projeto;

const ERRO  = 'E';
const AVISO = 'A';

use app\models\TabForm;
use app\models\VisAvisosErrosCampos;
use app\models\VisAtributosValores;
use app\models\VisGlossarios;
use app\modules\drenagem\models\base\TabGerais;
use app\modules\drenagem\models\base\TabRiscos;
use app\modules\drenagem\models\base\TabCobrancas;
use app\modules\drenagem\models\base\TabFinanceiros;
use yii\base\NotSupportedException;

trait TraitAlertas {
    
    public $alertasTotal;
    public $obrigatorios = [];
    
    public function init() {
        parent::init();
        
   /*     $form = TabForm::find()->where(['sgl_form' => static::FORM])->asArray()->one();
			
		$tipo = VisAtributosValores::find()
            ->select('cod_atributos_valores')
            ->where(['sgl_chave' => 'tipo-aviso-erro', 'sgl_valor' => static::TIPO])
            ->column()[0]
        ;
			*/
		
		
       /* $alertas = VisAvisosErrosCampos::find()
            ->select(['cod_aviso_erro', 'txt_codigo', 'txt_descricao', 'txt_formula', 'sgl_cod_info', 'bln_obrigatorio', 'dependencia_glossario_fk', 'dependencia_apresentacao_fk', 'sgl_dependencia_apresentacao'])
            ->where([
                //'fk_attr_formulario' => $form['cod_form'],
				//
                //'fk_attr_tipo_aviso_erro' => $tipo,
				'sgl_form'=>static::FORM,
				'sgl_valor'=>static::TIPO,
				'num_ano_ref' =>  \Yii::$app->params['ano-ref'],
                'bln_ativo' => true,
                'dte_exclusao' => null,
            ])
			->orderBy('txt_codigo, sgl_cod_info')
            ->asArray()
            ->all()
        ;*/
		
		
		
		$getData = function () {
			return  VisAvisosErrosCampos::find()
            ->select(['cod_aviso_erro','dsc_nom_info', 'txt_codigo', 'txt_descricao', 'txt_formula', 'sgl_cod_info', 
					  'bln_obrigatorio', 'dependencia_glossario_fk', 'dependencia_apresentacao_fk', 'sgl_dependencia_apresentacao'])
            ->where([
                //'fk_attr_formulario' => $form['cod_form'],
				//
                //'fk_attr_tipo_aviso_erro' => $tipo,
				'sgl_form'=>static::FORM,
				'sgl_valor'=>static::TIPO,
				'num_ano_ref' =>  \Yii::$app->params['ano-ref'],
                'bln_ativo' => true,
                'dte_exclusao' => null,
            ])
			->orderBy('txt_codigo, sgl_cod_info')
            ->asArray()
            ->all();
			;
		};

		
		if (\Yii::$app->params['habilitar-cache-global']) {
			$cacheKey = [			
				'sgl_form'=>static::FORM,
				'sgl_valor'=>static::TIPO,
				'num_ano_ref' =>  \Yii::$app->params['ano-ref'],
                'bln_ativo' => true,
                'dte_exclusao' => null];
	
			if (($dados = \Yii::$app->cache->get($cacheKey)) === false) {
				$dados = $getData();
				\Yii::$app->cache->set($cacheKey, $dados);
			}
		} else {
			$dados = $getData();
		}
		
		$alertas = $dados;
				
        foreach ($alertas as $key => $alerta) {
			
			if($alerta['txt_codigo']=='E000'){
				$this->obrigatorios[] = [
						'txt_codigo' => 'E000',
						'txt_descricao' => 'Campo de preenchimento obrigatório',
						'txt_formula' => "{$alerta['sgl_cod_info']}: Campo não informado",
						'sgl_cod_info' => [$alerta['sgl_cod_info']],
						'dependencia_glossario_fk' => $alerta['dependencia_glossario_fk'],
						'dependencia_apresentacao_fk' => $alerta['dependencia_apresentacao_fk'],
						'sgl_dependencia_apresentacao' => $alerta['sgl_dependencia_apresentacao'],
						'tooltip' => [$alerta['sgl_cod_info']=>$alerta['dsc_nom_info']],
					];
			}else if (!isset($this->alertas[$alerta['txt_codigo']])) {
                $this->alertas[$alerta['txt_codigo']] = $alerta;
                $this->alertas[$alerta['txt_codigo']]['sgl_cod_info'] = [$alerta['sgl_cod_info']];
				$this->alertas[$alerta['txt_codigo']]['tooltip'][$alerta['sgl_cod_info']] = $alerta['dsc_nom_info'];
            } else {
                $this->alertas[$alerta['txt_codigo']]['sgl_cod_info'][] = $alerta['sgl_cod_info'];
				$this->alertas[$alerta['txt_codigo']]['tooltip'][$alerta['sgl_cod_info']] = $alerta['dsc_nom_info'];
            }
        }
	
		/**
         * Campos de preenchimento obrigatório
         */
        /*if (self::TIPO == \projeto\ERRO) {
            $obrigatorios = VisGlossarios::find()
                ->select(['sgl_cod_info', 'dependencia_glossario_fk', 'dependencia_apresentacao_fk', 'sgl_dependencia_apresentacao'])
                ->where([
                    'num_ano_ref' => $this->app->params['ano-ref'],
                    'sgl_fam_info' => static::FORM,
                    'bln_info_ativa' => true,
                    'bln_obrigatorio' => true,
                ])->all()
            ;

            foreach ($obrigatorios as $campo) {

                $this->obrigatorios[] = [
					'txt_codigo' => 'E000',
					'txt_descricao' => 'Campo de preenchimento obrigatório',
					'txt_formula' => "{$campo->sgl_cod_info}: Campo não informado",
					'sgl_cod_info' => [$campo->sgl_cod_info],
					'dependencia_glossario_fk' => $campo->dependencia_glossario_fk,
					'dependencia_apresentacao_fk'=>$campo->dependencia_apresentacao_fk,
					'sgl_dependencia_apresentacao' => $campo->sgl_dependencia_apresentacao,
					
				];
			}
        }*/
		
    }

    public function calcular(array $alertas = []) {
		$mensagens = [];
		if (empty($alertas)) { // calcula todos
			$arrCodigos = array_keys($this->alertas);
		} else { // calcula específicos
			$arrCodigos = $alertas;
		}

		foreach ($arrCodigos as $alerta) {
			if (!method_exists($this, $alerta)) {
				continue;
				throw new NotSupportedException('[' . static::className() . '] Alerta não codificado: ' . $alerta);
			}

			if ($this->$alerta()) {
				$mensagens[$alerta] = $this->alertas[$alerta];
			}
		}

		foreach ($this->obrigatorios as $item) {

			if (
				
				$this->obrigatorio(strtolower($item['sgl_cod_info'][0])) &&
				(
				array_key_exists(strtolower($item['sgl_cod_info'][0]), $this->attributes) ||
				(
				array_key_exists(strtolower($item['sgl_cod_info'][0]), get_object_vars($this)) &&
				( isset($this->{strtolower($item['sgl_cod_info'][0])}) && ($this->{strtolower($item['sgl_cod_info'][0])} !== null ) ||
					$this->{strtolower($item['sgl_cod_info'][0])} !== '')
				)
				)
			) {
						
				if (isset($item['dependencia_glossario_fk'])) {
					
					$dependencia = VisGlossarios::find()->select('sgl_cod_info, sgl_tipo_apresentacao')->where(['cod_glossario' => $item['dependencia_glossario_fk']])->asArray()->one();
					//$dependencia = VisGlossarios::findOneAsArray(['cod_glossario' => $item['dependencia_glossario_fk']]);

					$sgl_cod_info = strtolower($dependencia['sgl_cod_info']);
					$apresentacao = $item['dependencia_apresentacao_fk'] . '@' . $item['sgl_dependencia_apresentacao'];

					if ($dependencia['sgl_tipo_apresentacao'] == 'dropdownlist') {

						if (( $this->$sgl_cod_info == $apresentacao)) {

							$mensagens['E000'][$item['sgl_cod_info'][0]] = $item;
						}
					} elseif ($dependencia['sgl_tipo_apresentacao'] == 'checkboxlist') {

						if ((!empty($this->$sgl_cod_info) ) && in_array($apresentacao, $this->$sgl_cod_info)) {

							$mensagens['E000'][$item['sgl_cod_info'][0]] = $item;
						}
					} else {

						$mensagens['E000'][$item['sgl_cod_info'][0]] = $item;
					}
					//$this->dd($mensagens);
				} else {
					$mensagens['E000'][$item['sgl_cod_info'][0]] = $item;
				}
			}
		}

		return $mensagens;
	}

	public function calcularAssoc(array $alertas = [], $tipo) {
		$mensagens = [];
		foreach ($alertas as $assoc) {
			
			foreach ($this->$assoc as $key => $assocClass) {
			
				foreach ($assocClass->$tipo->alertas as $alerta) {
					
					if (!method_exists($assocClass->$tipo, $alerta['txt_codigo'])) {
						continue;
						throw new NotSupportedException('[' . static::className() . '] Alerta não codificado: ' . $alerta);
					}

					if ($assocClass->$tipo->$alerta['txt_codigo']()) {
						$entao =  $assocClass->$tipo->alertas[$alerta['txt_codigo']];
						
						if(count($mensagens)>0 && array_key_exists( $entao['txt_codigo'], $mensagens)){						
							$mensagens[$entao['txt_codigo']]['linha'][] = ($key+1);
						}else{
							$mensagens[$entao['txt_codigo']] = $entao;
							$mensagens[$entao['txt_codigo']]['tabela'] = $assocClass->getDescricaoTabela();
							$mensagens[$entao['txt_codigo']]['linha'][] = ($key+1);
						}
					}
				}

				/*foreach ($this->obrigatorios as $item) {
					if ($this->obrigatorio(strtolower($item['sgl_cod_info'][0])) && array_key_exists(strtolower($item['sgl_cod_info'][0]), $this->attributes)) {
						$mensagens['E000'][$item['sgl_cod_info'][0]] = $item;
					}
				}*/
			}
		}

		return $mensagens;
	}

	public function getAlertasTotal() {
        $alertas = $this->calcular();

        if ($alertas) {
            if (array_key_exists('E000', $alertas)) {
                $this->alertasTotal += count($alertas['E000']);
                unset($alertas['E000']);
            }

            foreach ($alertas as $value) {
                foreach ($value['sgl_cod_info'] as $value2) {
                    $sigla = (substr($value2, 0, 2) == 'AD') ? 'FN' : substr($value2, 0, 2);
                    if ($sigla == $this::FORM) {
                        $this->alertasTotal++;
                    }
                }
            }
        }

        return $this->alertasTotal;
    }

    public function obrigatorio($campo) {

		//retorna true caso o valor seja 0.
		if(isset($this->$campo) && ($this->$campo)=='0'){ 
			return false; 
		}
		
        return empty ($this->$campo);
    }
    
	/**
	 * tabGerais
	 * @return Model
	 */
	public function tabGerais($base = true) {

		if ($base) {
			$model = TabGerais::find()->where([
				'participacao_fk' => (!isset($this->participacao_fk)) ? $this->session->get('Formularios')['cod_participacao'] : $this->participacao_fk,
				'num_ano_ref' => (!isset($this->num_ano_ref)) ? $this->session->get('Formularios')['ano_ref'] : $this->num_ano_ref
			])
			->one();
		} else {
			\app\modules\drenagem\models\TabGerais::find()->where([
				'participacao_fk' => (!isset($this->participacao_fk)) ? $this->session->get('Formularios')['cod_participacao'] : $this->participacao_fk,
				'num_ano_ref' => (!isset($this->num_ano_ref)) ? $this->session->get('Formularios')['ano_ref'] : $this->num_ano_ref
			])
			->one();
		}

		
		return $model;
	}

	/**
	 * tabRiscos
	 * @return Model
	 */
	public function tabRiscos($base = true) {

		if ($base) {
			$model = TabRiscos::find()->where([
					'participacao_fk' => $this->participacao_fk,
					'num_ano_ref' => $this->num_ano_ref
				])
				->one();
		} else {
			$model = \app\modules\drenagem\models\TabRiscos::find()->where([
					'participacao_fk' => $this->participacao_fk,
					'num_ano_ref' => $this->num_ano_ref
				])
				->one();
		}

		return $model;
	}

	/**
	 * tabCobrancas
	 * @return Model
	 */
	public function tabCobrancas($base = true) {

		if ($base) {
			$model = TabCobrancas::find()->where([
					'participacao_fk' => $this->participacao_fk,
					'num_ano_ref' => $this->num_ano_ref
				])
				->one();
		} else {

			$model = \app\modules\drenagem\models\TabCobrancas::find()->where([
					'participacao_fk' => $this->participacao_fk,
					'num_ano_ref' => $this->num_ano_ref
				])
				->one();
		}

		return $model;
	}

	/**
	 * tabFinanceiros
	 * @return Model
	 */
	public function tabFinanceiros($base = true) {

		if ($base) {
			$model = TabFinanceiros::find()->where([
					'participacao_fk' => $this->participacao_fk,
					'num_ano_ref' => $this->num_ano_ref
				])
				->one();
		} else {
			$model = \app\modules\drenagem\models\TabFinanceiros::find()->where([
					'participacao_fk' => $this->participacao_fk,
					'num_ano_ref' => $this->num_ano_ref
				])
				->one();
		}

		return $model->find()->where([
					'participacao_fk' => $this->participacao_fk,
					'num_ano_ref' => $this->num_ano_ref
				])
				->one();
	}

}
