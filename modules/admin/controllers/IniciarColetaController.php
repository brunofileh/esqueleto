<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\TabMunicipiosDesastres;

class IniciarColetaController extends \projeto\web\Controller {

	public function actionAtualizarPopulacao() {
		$this->titulo = 'Atualizar População IBGE';


		$model = new \app\models\TabMunicipiosPopulacoes();

		$model->ano_ult = \app\models\TabMunicipiosPopulacoes::find()->select("max(ano_ref) as ano_ref")->asArray()->one()['ano_ref'];

		$importacao = [];
		$model->setScenario('carregar');

		if (Yii::$app->request->post()) {
			$model->load(Yii::$app->request->post());
			$dados = \yii\web\UploadedFile::getInstance($model, 'file');

			if (array_key_exists('importar', Yii::$app->request->post())) {

				$transaction = Yii::$app->db->beginTransaction();
				$municipios = \Yii::$app->session->get('lista-municipio');

				try {
					$erros = false;
					ini_set('memory_limit', '512M');

					$tot_updt = $tot_incl = 0;

					foreach ($municipios as $key => $value) {
						$pop = \app\models\TabMunicipiosPopulacoes::findOne(['ano_ref' => $model->ano_ref, 'municipio_fk' => $value['municipio_fk']]);

						if (!$pop) {
							$pop_ant = \app\models\TabMunicipiosPopulacoes::find()->select("max(ano_ref) as ano_ref")->where("municipio_fk='{$value['municipio_fk']}'")->asArray()->one();
							$pop_ant = \app\models\TabMunicipiosPopulacoes::findOne(['ano_ref' => $pop_ant['ano_ref'], 'municipio_fk' => $value['municipio_fk']]);

							$pop = new \app\models\TabMunicipiosPopulacoes();

							$value['cod_faixa_populacional_fk'] = $pop_ant->cod_faixa_populacional_fk;
							$tot_incl++;
						} else {

							$tot_updt++;
							$value['cod_faixa_populacional_fk'] = $pop['cod_faixa_populacional_fk'];
						}

						$pop->attributes = $value;
						$pop->ano_ref = $model->ano_ref;
						$pop->save();
						if ($pop->errors) {
							$erros = true;
						}
					}

					if (!$erros) {
						$msgTotal = null;
						if ($tot_incl > 0) {
							$msgTotal .= "Total de municípios incluídos: {$tot_incl}<br/>";
						}

						if ($tot_updt > 0) {
							$msgTotal .= "Total de municípios atualizado: {$tot_updt}<br/>";
						}
						$tot_imp = $tot_incl + $tot_updt;
						$msgTotal .= "<b>Total de municípios importador: {$tot_imp}</b>";

						$transaction->commit();

						$this->session->setFlash('success', "Importação do ano de <b>{$model->ano_ref}</b> realizada com sucesso.<br / >{$msgTotal}");

						\Yii::$app->session->set('lista-municipio', null);

						return $this->redirect(['atualizar-populacao']);
					} else {
						$this->session->setFlash('success', 'Erro na importação favor verificar os dados do arquivo.');
						$transaction->rollBack();
					}
				} catch (\Exception $e) {
					$transaction->rollBack();
					throw $e;
				}
			} else {

				$importacao = $this->importExcel($dados->tempName, Yii::$app->request->post()['TabMunicipiosPopulacoes']);
				if ($importacao['is_valid_pop_tot']) {
					\Yii::$app->session->set('lista-municipio', null);
					$this->session->setFlash('danger', 'Não é permitido a inclusão de População Total igual a 0 (zero). Municípios ' . implode(', ', $importacao['is_valid_pop_tot']));
				} else {
					$model->setScenario('importar');
				}

				if ($importacao['is_valid_rules']) {
					\Yii::$app->session->set('lista-municipio', null);
					$this->session->setFlash('danger', $importacao['is_valid_rules']);
				}

				if ($importacao['is_valid_layout']) {

					$this->session->setFlash('danger', "Layout de importação incorreto.");
				}


				if ($importacao['is_valid_pop_ano']) {

					$this->session->setFlash('warning', "Existe população na base de dados com o Ano de Referência <b>{$model->ano_ref}</b> informado.");
				}
			}
		} else {

			\Yii::$app->session->set('lista-municipio', null);
		}

		return $this->render('atualizar-populacao', ['model' => $model, 'importacao' => $importacao]);
	}

	/**
	 * Método para geração da tabela que será apresentada na página e criação dos dados na sessão para importação
	 * Padrão do atributo $arquivo:
	 *  
	  [0] => SGL_EST
	  [1] => COD_IBGE
	  [2] => COD_MUN
	  [3] => NOM_MUN
	  [4] => POP_TOT
	  [5] => POP_URB
	 * 
	 * @param Array $arquivo Array com os dados a serem validados para importação
	 * @return string
	 */
	public function importExcel($inputFiles, $post) {
		ini_set('memory_limit', '512M');
		$arr_dados = [];
		try {
			$inputFileType = \PHPExcel_IOFactory::identify($inputFiles);
			$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFiles);
		} catch (Exception $ex) {
			die('Error');
		}

		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();


		$is_valid_pop_tot = [];
		$is_valid_layout = null;
		$is_valid_rules = null;

		//$row is start 2 because first row assigned for heading.         
		$i = 0;
		for ($row = 2; $row <= $highestRow; ++$row) {

			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

			if (!$rowData[0][2]) {
				continue;
			}

			if (!(strlen(trim($rowData[0][0])) == 2 && strlen(trim($rowData[0][1])) == 7 && strlen(trim($rowData[0][2])) == 6)) {
				$is_valid_layout = true;
				break;
			}
			$arr_dados[$i]['ano_ref'] = $post['ano_ref'];
			$arr_dados[$i]['cod_mun'] = $rowData[0][2];

			$nome_municipio = str_replace('*', '', $rowData[0][3]);
			$nome_municipio = str_replace("'", ' ', $nome_municipio);
			$arr_dados[$i]['nom_mun'] = $nome_municipio;

			$arr_dados[$i]['municipio_fk'] = (string) $rowData[0][2];

			$arr_dados[$i]['sgl_est'] = $rowData[0][0];
			$arr_dados[$i]['pop_tot'] = $rowData[0][4];

			if (empty($rowData[0][4])) {
				$is_valid_pop_tot[$i] = $rowData[0][2];

				$arr_dados[$i]['pop_urb'] = $rowData[0][5];

				$arr_dados[$i]['pop_rur'] = 0;

				// TX_URB - calculado: POP_URB / POP_TOT
				$arr_dados[$i]['tx_urb'] = 0;
			} else {

				$arr_dados[$i]['pop_urb'] = $rowData[0][5];

				$arr_dados[$i]['pop_rur'] = $rowData[0][4] - $rowData[0][5];

				// TX_URB - calculado: POP_URB / POP_TOT
				$arr_dados[$i]['tx_urb'] = ($rowData[0][5] / $rowData[0][4]);
			}

			$pop = new \app\models\TabMunicipiosPopulacoes();

			$pop->attributes = $arr_dados[$i];

			if (!$pop->validate()) {

				foreach ($pop->errors as $key => $value) {
					foreach ($value as $key2 => $value2) {
						$is_valid_rules .= $rowData[0][2] . ' - ' . $value2 . '<br / >';
					}
				}
			}

			$i++;
		}

		\Yii::$app->session->set('lista-municipio', $arr_dados);
		$total = count($arr_dados);
		$dataProvider = new \yii\data\ArrayDataProvider([
			'id' => 'lista-importacao',
			'allModels' => $arr_dados,
			'sort' => false,
			'pagination' => ['pageSize' => false],
		]);
		$is_valid_pop_ano = [];
		$is_valid_pop_ano = \app\models\TabMunicipiosPopulacoes::findOne(['ano_ref' => $post['ano_ref']]);



		return [
			'dataProvider' => $dataProvider,
			'total' => $total,
			'is_valid_pop_tot' => $is_valid_pop_tot,
			'is_valid_layout' => $is_valid_layout,
			'is_valid_pop_ano' => $is_valid_pop_ano,
			'is_valid_rules' => $is_valid_rules
		];
	}

	/**
	 * Método para geração da tabela que será apresentada na página e criação dos dados na sessão para importação
	 * Padrão do atributo $arquivo:
	 *  
	  [0] => SGL_EST
	  [1] => COD_IBGE
	  [2] => COD_MUN
	  [3] => NOM_MUN
	  [4] => POP_TOT
	  [5] => POP_URB
	 * 
	 * @param Array $arquivo Array com os dados a serem validados para importação
	 * @return string
	 */
	public function importExcelColeta($inputFiles, $post) {
		ini_set('memory_limit', '512M');
		$arr_dados = [];
		try {
			$inputFileType = \PHPExcel_IOFactory::identify($inputFiles);
			$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFiles);
		} catch (Exception $ex) {
			die('Error');
		}

		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();

		$is_valid_layout = null;

		//$row is start 2 because first row assigned for heading.         
		$i = 0;
		//$modulo = \app\modules\admin\models\TabModulos::find()->where(['id'=>'drenagem'])->asArray()->one();
		$situacao = \app\models\VisAtributosValores::find()->where(['sgl_chave' => 'rlc-situacao-preenchimento-snis'])->indexBy('sgl_valor')->asArray()->all();
		
		for ($row = 2; $row <= $highestRow; ++$row) {

			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

			if (!$rowData[0][2]) {
				continue;
			}

			if (!(strlen(trim($rowData[0][0])) == 6 && strlen(trim($rowData[0][1])) == 4 && strlen(trim($rowData[0][3])) == 4)) {
				$is_valid_layout = true;
				break;
			}

			$arr_dados[$i]['cod_municipio_fk'] = trim($rowData[0][0]);
			$arr_dados[$i]['snis_ae_ano'] = trim($rowData[0][1]);
			$arr_dados[$i]['snis_ae_sit'] = trim($rowData[0][2]);
			$arr_dados[$i]['snis_rs_ano'] = trim($rowData[0][3]);
			$arr_dados[$i]['snis_rs_sit'] = trim($rowData[0][4]);



			switch ($rowData[0][2]) {
				case '0': $sit = 'N.I.';
					break;
				case '1': $sit = "S.R.";
					break;
				case '2': $sit = "F.R.";
					break;
				case '3': $sit = "F.P.";
					break;
				case '4': $sit = "A.A.";
					break;
				case '5': $sit = "A.T.";
					break;
				case '6': $sit = "R.I.";
					break;
				case '7': $sit = "F.I.";
					break;
				case '8': $sit = "A.F.";
					break;
				case '9': $sit = "C.N.";
					break;
				case '10': $sit = "S.E.";
					break;
			}

			$arr_dados[$i]['sgl_valor_ae'] = $situacao[$sit]['sgl_valor'];
			$arr_dados[$i]['cod_atributos_valores_ae'] = $situacao[$sit]['cod_atributos_valores'];
			$arr_dados[$i]['dsc_descricao_ae'] = $situacao[$sit]['dsc_descricao'];
			
			switch ($rowData[0][4]) {
				case '0': $sit = 'N.I.';
					break;
				case '1': $sit = "S.R.";
					break;
				case '2': $sit = "F.R.";
					break;
				case '3': $sit = "F.P.";
					break;
				case '4': $sit = "A.A.";
					break;
				case '5': $sit = "A.T.";
					break;
				case '6': $sit = "R.I.";
					break;
				case '7': $sit = "F.I.";
					break;
				case '8': $sit = "A.F.";
					break;
				case '9': $sit = "C.N.";
					break;
				case '10': $sit = "S.E.";
					break;
			}
			
			

			$arr_dados[$i]['sgl_valor_rs'] = $situacao[$sit]['sgl_valor'];
			$arr_dados[$i]['cod_atributos_valores_rs'] = $situacao[$sit]['cod_atributos_valores'];
			$arr_dados[$i]['dsc_descricao_rs'] = $situacao[$sit]['dsc_descricao'];
			
			$i++;
		}

		\Yii::$app->session->set('lista-coleta', $arr_dados);
		$total = count($arr_dados);
		$dataProvider = new \yii\data\ArrayDataProvider([
			'id' => 'lista-coleta',
			'allModels' => $arr_dados,
			'sort' => false,
			'pagination' => ['pageSize' => false],
		]);

		return [
			'dataProvider' => $dataProvider,
			'total' => $total,
			'is_valid_layout' => $is_valid_layout,
		];
	}

	public function actionAtualizarColetaSnis() {
		$this->titulo = 'Atualizar Coletar AE e RS';

		$model = new \app\modules\drenagem\models\TabParticipacoesSearch();
		$model->ano_ref = \yii::$app->params['ano-ref'];
		$importacao = [];

		if (Yii::$app->request->post()) {
			$model->load(Yii::$app->request->post());
			$dados = \yii\web\UploadedFile::getInstance($model, 'file');

			if (array_key_exists('importar', Yii::$app->request->post())) {

				$transaction = Yii::$app->db->beginTransaction();
				$municipios = \Yii::$app->session->get('lista-municipio');

				try {
					$erros = false;
					ini_set('memory_limit', '512M');

					$tot_updt = $tot_incl = 0;

					foreach ($municipios as $key => $value) {
						$pop = \app\models\TabMunicipiosPopulacoes::findOne(['ano_ref' => $model->ano_ref, 'municipio_fk' => $value['municipio_fk']]);

						if (!$pop) {
							$pop_ant = \app\models\TabMunicipiosPopulacoes::find()->select("max(ano_ref) as ano_ref")->where("municipio_fk='{$value['municipio_fk']}'")->asArray()->one();
							$pop_ant = \app\models\TabMunicipiosPopulacoes::findOne(['ano_ref' => $pop_ant['ano_ref'], 'municipio_fk' => $value['municipio_fk']]);

							$pop = new \app\models\TabMunicipiosPopulacoes();

							$value['cod_faixa_populacional_fk'] = $pop_ant->cod_faixa_populacional_fk;
							$tot_incl++;
						} else {

							$tot_updt++;
							$value['cod_faixa_populacional_fk'] = $pop['cod_faixa_populacional_fk'];
						}

						$pop->attributes = $value;
						$pop->ano_ref = $model->ano_ref;
						$pop->save();
						if ($pop->errors) {
							$erros = true;
						}
					}

					if (!$erros) {
						$msgTotal = null;
						if ($tot_incl > 0) {
							$msgTotal .= "Total de municípios incluídos: {$tot_incl}<br/>";
						}

						if ($tot_updt > 0) {
							$msgTotal .= "Total de municípios atualizado: {$tot_updt}<br/>";
						}
						$tot_imp = $tot_incl + $tot_updt;
						$msgTotal .= "<b>Total de municípios importador: {$tot_imp}</b>";

						$transaction->commit();

						$this->session->setFlash('success', "Importação do ano de <b>{$model->ano_ref}</b> realizada com sucesso.<br / >{$msgTotal}");

						\Yii::$app->session->set('lista-municipio', null);

						return $this->redirect(['atualizar-populacao']);
					} else {
						$this->session->setFlash('success', 'Erro na importação favor verificar os dados do arquivo.');
						$transaction->rollBack();
					}
				} catch (\Exception $e) {
					$transaction->rollBack();
					throw $e;
				}
			} else {


				$importacao = $this->importExcelColeta($dados->tempName, Yii::$app->request->post()['TabParticipacoesSearch']);

				
				if ($importacao['is_valid_layout']) {

					$this->session->setFlash('danger', "Layout de importação incorreto.");
				}

			}
		} else {

			\Yii::$app->session->set('lista-snis-ae-rs', null);
		}

		return $this->render('atualizar-coleta-snis', ['model' => $model, 'importacao' => $importacao]);
	}

	/**
	 * metodo de importacao da planilha de desastres
	 * 
	 * campos da planilha
	 * 0 => Código IBGE
	 * 1 => Código do município
	 * 2 => Município
	 * 3 => UF
	 * 4 => Tipo de desastre
	 * 5 => Desastres
	 * 6 => Óbitos
	 * 7 => Desabrigados
	 * 8 => Desalojados
	 */
	public function actionImportacaoDesastres() {
		$this->titulo = 'Importação de desastres';

		$model = new TabMunicipiosDesastres();
		$model->setScenario('abrir_arquivo');

		$importacao = [];
		if (Yii::$app->request->post()) {
			$model->load(Yii::$app->request->post());
			$dados = \yii\web\UploadedFile::getInstance($model, 'file');

			if (array_key_exists('importar_dados', Yii::$app->request->post())) {
				// ao clicar no botao "Importar arquivo"
				$transaction = Yii::$app->db->beginTransaction();
				$municipios = Yii::$app->session->get('lista-municipio');

				try {
					$erros = false;

					ini_set('memory_limit', '512M');

					$tot_alte = $tot_incl = 0;
					foreach ($municipios as $key => $value) {
						$des = TabMunicipiosDesastres::findOne(['municipio_fk' => $value['cod_municipio'], 'ano_ref' => $model->ano_ref, 'txt_tipo_desastre' => $value['tipo_desastre']]);

						if (!$des) {
							$des = new TabMunicipiosDesastres();
							$tot_incl++;
						} else {
							$tot_alte++;
						}

						$des->municipio_fk      = $value['cod_municipio'];
						$des->ano_ref           = $model->ano_ref;
						$des->txt_tipo_desastre = $value['tipo_desastre'];
						$des->num_desastres     = $value['num_desastres'];
						$des->num_obitos        = $value['num_obitos'];
						$des->num_desabrigados  = $value['num_desabrigados'];
						$des->num_desalojados   = $value['num_desalojados'];

						$des->save();

						if ($des->errors) {
							$erros = true;
						}
					}

					if (!$erros) {
						$msgTotal = null;
						$formatter = Yii::$app->formatter;

						if ($tot_incl > 0) {
							$msgTotal .= "Total de desastres incluídos: {$formatter->asInteger($tot_incl)}<br />";
						}

						if ($tot_alte > 0) {
							$msgTotal .= "Total de desastres alterados: {$formatter->asInteger($tot_alte)}<br />";
						}

						$tot = $tot_incl + $tot_alte;

						$msgTotal .= "<b>Total de desastres importados: {$formatter->asInteger($tot)}</b>";

						$transaction->commit();

						$this->session->setFlash('success', "Importação de desastres do ano de <b>{$model->ano_ref}</b> realizada com sucesso.<br /><br />{$msgTotal}");

						Yii::$app->session->set('lista-municipio', null);

						return $this->redirect(['importacao-desastres']);
					} else {
						$this->session->setFlash('danger', 'Erro ao importar os dados do arquivo.');
						$transaction->rollBack();
					}
				} catch (\Exception $e) {
					$transaction->rollBack();
					throw $e;
				}
			} else {
				// ao clicar no botao "Abrir arquivo"
				ini_set('memory_limit', '512M');

				$arr_dados = [];
				try {
					$inputFileType = \PHPExcel_IOFactory::identify($dados->tempName);
					$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($dados->tempName);
				} catch (Exception $ex) {
					$this->dd('Erro ao abrir o arquivo.');
				}

				$sheet = $objPHPExcel->getSheet(0);
				$highestRow = $sheet->getHighestRow();
				$highestColumn = $sheet->getHighestColumn();

				// desconsiderar a primeira linha com o cabecalho e comecar da segunda linha
				$post = Yii::$app->request->post()['TabMunicipiosDesastres'];

				$i = 0;
				$j = 1;
				$validate_rules = null;
				for ($row = 2; $row <= $highestRow; ++$row) {
					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
					
					$arr_dados[$i]['cod_ibge'] = $rowData[0][0];
					$arr_dados[$i]['cod_municipio'] = (string) $rowData[0][1];
					$arr_dados[$i]['nome_municipio'] = $rowData[0][2];
					$arr_dados[$i]['uf'] = $rowData[0][3];
					$arr_dados[$i]['tipo_desastre'] = strtoupper($rowData[0][4]);
					$arr_dados[$i]['num_desastres'] = (!$rowData[0][5] ? 0 : $rowData[0][5]);
					$arr_dados[$i]['num_obitos'] = (!$rowData[0][6] ? 0 : $rowData[0][6]);
					$arr_dados[$i]['num_desabrigados'] = (!$rowData[0][7] ? 0 : $rowData[0][7]);
					$arr_dados[$i]['num_desalojados'] = (!$rowData[0][8] ? 0 : $rowData[0][8]);
					
					$des = new TabMunicipiosDesastres();
					$des->municipio_fk      = $arr_dados[$i]['cod_municipio'];
					$des->ano_ref           = $model->ano_ref;
					$des->txt_tipo_desastre = $arr_dados[$i]['tipo_desastre'];
					$des->num_desastres     = $arr_dados[$i]['num_desastres'];
					$des->num_obitos        = $arr_dados[$i]['num_obitos'];
					$des->num_desabrigados  = $arr_dados[$i]['num_desabrigados'];
					$des->num_desalojados   = $arr_dados[$i]['num_desalojados'];
					
					if (!$des->validate()) {
						if ($j == 1)
							$validate_rules = 'Erro(s) ao abrir o arquivo:<br /><br />';
						
						foreach ($des->errors as $key => $value) {
							foreach ($value as $key2 => $value2) {
								$validate_rules .= $j . ') ' . $rowData[0][2] . ' - ' . $rowData[0][3] . ': ' . $value2 . '<br / >';
								$j++;
							}
						}
					}
					
					$i++;
				}
				
				if ($validate_rules) {
					$this->session->setFlash('danger', $validate_rules);
				} else {
					Yii::$app->session->set('lista-municipio', $arr_dados);

					$importacao = new \yii\data\ArrayDataProvider([
						'id' => 'lista-importacao',
						'allModels' => $arr_dados,
						'sort' => false,
						'pagination' => [
							'pageSize' => false
						],
					]);

					$model->setScenario('importar_dados');					
				}
			}
		} else {
			Yii::$app->session->set('lista-municipio', null);
		}
		
		// consultar desastres importados por ano
		$arr_dados_desastres_importados = TabMunicipiosDesastres::find()
			->select('ano_ref
				,txt_tipo_desastre as tipo_desastre
				,sum(num_desastres) as num_desastres
				,sum(num_obitos) as num_obitos
				,sum(num_desabrigados) as num_desabrigados
				,sum(num_desalojados) as num_desalojados')
			->groupBy('ano_ref
				,txt_tipo_desastre')
			->orderBy('ano_ref desc
				,txt_tipo_desastre')
			->asArray()
			->all();
		
		$desastres_importados = new \yii\data\ArrayDataProvider([
			'id'         => 'lista-desastres-importados',
			'allModels'  => $arr_dados_desastres_importados,
			'sort'       => false,
			'pagination' => [
				'pageSize' => 10,
			],
		]);
		
		return $this->render('importacao-desastres', [
			'model'                => $model,
			'importacao'           => $importacao,
			'desastres_importados' => $desastres_importados
		]);
	}

}
