<?php

namespace projeto\service;

use app\modules\drenagem\models\TabIndicadores;
use app\models\TabGlossariosIndicadores;

class Indicadores extends \yii\base\Object
{
	use \projeto\Atalhos;
	
	protected $tabela;
	protected $prestadores = []; // lista de prestadores
	protected $anoRef; // ano de referência
	protected $anoAnt; // ano anterior

	protected $dados = []; // dados das tabelas da coleta
	protected $result = []; // resultado do cálculo
	
	protected $dadosPrestador = []; // dados do prestador
	protected $prestador; // cod_prestador
	protected $participacao; // cod_participacao
	protected $municipio; // cod_municipio
	
	public function init()
	{
		parent::init();
		$this->configAtalhos();
	}
	
	public function loadDados(array $prestadores = [])
	{
		$this->prestadores = $prestadores;
		
		$sqlDadosParams = [':ano_ref' => $this->anoRef];
		$sqlDadosIn = $this->db->getQueryBuilder()->buildCondition(['IN', 'p.cod_prestador', $prestadores], $sqlDadosParams);
		$sqlDados = "
			select 	pt.cod_participacao, mp.cod_modulo_prestador, p.cod_prestador, pt.ano_ref, p.cod_municipio_fk as cod_municipio,
					ge002, ge006, ge007, ge008, 
					ad001, ad002, ad003, fn005, fn012, fn016,  
					cb003,
					ie019, ie017, ie024, ie044, ie031, ie032, ie035, ie035, ie036, ie033, ie021, ie019, ie034,
					ri013, ri029, ri067

			from drenagem.tab_participacoes pt
			join rlc_modulos_prestadores mp on (pt.cod_modulo_prestador_fk = mp.cod_modulo_prestador)
			join tab_prestadores p on (p.cod_prestador = mp.cod_prestador_fk)

			left join drenagem.tab_gerais ge on (ge.participacao_fk = pt.cod_participacao and ge.num_ano_ref = pt.ano_ref::smallint)
			left join drenagem.tab_financeiros fn on (fn.participacao_fk = pt.cod_participacao and fn.num_ano_ref = pt.ano_ref::smallint)
			left join drenagem.tab_cobrancas cb on (cb.participacao_fk = pt.cod_participacao and cb.num_ano_ref = pt.ano_ref::smallint)
			left join drenagem.tab_infraestruturas ie on (ie.participacao_fk = pt.cod_participacao and ie.num_ano_ref = pt.ano_ref::smallint)
			left join drenagem.tab_riscos ri on (ri.participacao_fk = pt.cod_participacao and ri.num_ano_ref = pt.ano_ref::smallint)


			where 	true
					and pt.ano_ref = :ano_ref
					and {$sqlDadosIn}
		";
		
		$sqlBacias = '
			select cod_infraestrutura_fk, participacao_fk, ie058
			from drenagem.tab_infraestruturas_bacias ieb
			join drenagem.tab_infraestruturas ie on (ie.cod_infraestrutura = ieb.cod_infraestrutura_fk)
			join drenagem.tab_participacoes pt on (pt.cod_participacao = ie.participacao_fk)
			where	true
					and ie.participacao_fk = :participacao
		';
		
		$dados = $this->db->createCommand($sqlDados, $sqlDadosParams)->queryAll();
		foreach ($dados as $k => &$row) {
			$bacias = $this->db->createCommand($sqlBacias, [':participacao' => $row['cod_participacao']])->queryAll();
			$row['bacias'] = $bacias;
			$this->dados[$row['cod_prestador']] = $row;
			unset($row[$k]);
		}
	}
	
	public function getSomatorio(array $array, $campo)
	{
		$soma = 0;
		foreach ($array as $item) {
			$soma += $item[$campo];
		}
		return $soma;
	}
	
	public function calcular(array $indicadores = [])
	{
		$trans = $this->db->beginTransaction();
		try {
			foreach ($this->prestadores as $prestador) {

				$this->dadosPrestador = $this->dados[$prestador];
				$this->prestador = $this->dadosPrestador['cod_prestador'];
				$this->participacao = $this->dadosPrestador['cod_participacao'];
				$this->municipio = $this->dadosPrestador['cod_municipio'];

				foreach ($indicadores as $ind) {
					$ind = strtolower($ind);
					if (!method_exists($this, $ind)) {
						$class = $this->className();
						throw new \Exception("Método '$ind' não encontrado na classe '$class'");
					}

					$this->result[$this->prestador][$ind] = $this->$ind();
				}

				$this->result[$this->prestador]['cod_participacao_fk'] = $this->participacao;
				$this->result[$this->prestador]['cod_prestador_fk'] = $this->prestador;
				$this->result[$this->prestador]['num_ano_ref'] = $this->anoRef;
				$this->result[$this->prestador]['cod_municipio_fk'] = $this->municipio;
			}
			
			$this->salvarTodos();
			$trans->commit();
		}
		catch (\Exception $e) {
			$trans->rollback();
			throw $e;
		}
	}
	protected function salvarTodos()
	{
		TabIndicadores::deleteAll(['cod_prestador_fk' => $this->prestadores, 'num_ano_ref' => $this->anoRef]);
		
		$campos = array_keys($this->result[$this->prestador]);
		$dados=[];
		foreach ($this->result as $item) {
			$dados[] = array_values($item);
		}

		$this->db->createCommand()->batchInsert(TabIndicadores::tableName(), $campos, $dados)->execute();
	}
	
	public function campo($nomeCampo)
	{
		if (!array_key_exists($nomeCampo, $this->dadosPrestador)) {
			throw new \Exception("[indicadores] '$nomeCampo' não encontrado no array de dados.");
		}
		
		return $this->dadosPrestador[$nomeCampo];
	}
	
	public function campos(array $campos)
	{
		$r = [];
		foreach ($campos as $campo) {
			$r[] = $this->campo($campo);
		}
		return $r;
	}
	
	public function roundUp($vlr, $p=2)
	{
		return round($vlr, $p, PHP_ROUND_HALF_UP);
	}
	
	public function getIndicadores()
	{
		return array_map(function($item) {
			return strtolower($item['sgl_indicador']);
		}, TabGlossariosIndicadores::find()
			->select('sgl_indicador')
			->where([
				'ano_ref' => $this->anoRef,
				'bln_indicador_ativo' => true,
//				'servico_fk' => 2,
			])
			->asArray()
			->all()
		);
	}
	
	public function getInfoIndicadores()
	{
		return TabGlossariosIndicadores::find()
			->where([
				'ano_ref' => $this->anoRef,
				'bln_indicador_ativo' => true,
//				'servico_fk' => 2,
			])
			->indexBy('sgl_indicador')
			->asArray()
			->all()
		;
	}
}