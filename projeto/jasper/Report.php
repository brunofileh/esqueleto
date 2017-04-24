<?php

namespace projeto\jasper;

require_once dirname(__FILE__) . '/jrs-rest-php-client-2.0.0/autoload.dist.php';

use Jaspersoft\Client\Client;
use app\models\TabJasperParametros;

/**
 * Geração de relatórios jasper
 * 
 * @example 
 * $rel = new \projeto\jasper\Report();
 * return $rel->download($rel::AP_CADASTRO_MUNICIPAL, [
 *	'ano_ref' => 2015,
 *	'cod_prestador' => [...],
 *  // 'IS_IGNORE_PAGINATION' => true,
 *  // Outros parâmetros do relatório
 * ]);
 */
class Report
{
	public $client;
	public $id;
	
	public function __construct()
	{
		set_time_limit(0);
		
		$params = \Yii::$app->params['drenagem'];
		$this->client = new Client($params['jasper-url'], $params['jasper-usr'], $params['jasper-pwd']);
	}
	
	public function run($relName, array $params = [], $tipo='xls')
	{
		try {
			return $this->client->reportService()->runReport($relName, $tipo, null, null, $params);
		}
		catch (\Exception $e) {
//			\projeto\Util::dd($e);
			throw $e;
		}
	}
	
	public function download($relName, array $params = [], $tipo='xls')
	{
		$report = $this->run($relName, $params, $tipo);
		$exp = explode('/', $relName);
		$filename = $exp[count($exp) -1] . '--' . date('d-m-Y-H-i') . ".$tipo";

		return \Yii::$app->response->sendContentAsFile($report, $filename)->send();
	}
	
	public function getId()
	{
		if (null === $this->id) {
			$this->setId();
		}
		
		return $this->id;
	}
	
	private function setId()
	{
		$this->id = md5(uniqid(rand(), true));
	}
	
	public function saveParams(array $params)
	{
		$relid = $this->getId();
		$trans = \Yii::$app->db->beginTransaction();
		
		try {
			foreach ($params['prestadores'] as $codPsv) {
				(new TabJasperParametros([
					'sgl_relid' => $relid,
					'cod_prestador_fk' => $codPsv,
				]))
				->save();
			}
			$trans->commit();
		}
		catch (\Exception $e) {
			$trans->rollback();
			throw $e;
		}
	}
	
	public function clearParams()
	{
		return TabJasperParametros::deleteAll(['sgl_relid' => $this->getId()]);
	}
}
 