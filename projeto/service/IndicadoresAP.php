<?php

namespace projeto\service;

class IndicadoresAP extends Indicadores
{
	public function __construct($anoRef = null)
	{
		parent::__construct();
		
		if (!$anoRef) {
			$anoRef = $this->app->params['ano-ref'];
		}
		$this->anoRef = (int) $anoRef;
		$this->anoAnt = $this->anoRef -1;
	}
	
	public function in001()
	{
		list($ad001, $ad003) = $this->campos(['ad001', 'ad003']);
		
		$r = null;
		if (is_numeric($ad001) && !empty((int) $ad003)) {
			$r = $this->roundUp((($ad001 / $ad003) * 100), 1);
		}
		
		return $r;
	}
	
	public function in002()
	{
		list($ad002, $ad003) = $this->campos(['ad002', 'ad003']);
		
		$r = null;
		if (is_numeric($ad002) && !empty((int) $ad003)) {
			$r = $this->roundUp((($ad002 / $ad003) * 100), 1);
		}
		
		return $r;
	}
	
	public function in005()
	{
		list($fn005, $ge007) = $this->campos(['fn005', 'ge007']);
		
		$r = null;
		if (is_numeric($fn005) && !empty((int) $ge007)) {
			$r = $this->roundUp(($fn005 / $ge007));
		}
		
		return $r;
	}
	
	public function in006()
	{
		list($fn005, $cb003) = $this->campos(['fn005', 'cb003']);
		
		$r = null;
		if (is_numeric($fn005) && !empty($cb003)) {
			$r = $this->roundUp(($fn005 / $cb003));
		}
		
		return $r;
	}
	
	public function in009()
	{
		list($fn016, $ge007) = $this->campos(['fn016', 'ge007']);
		
		$r = null;
		if (is_numeric($fn016) && !empty((int) $ge007)) {
			$r = $this->roundUp($fn016 / $ge007);
		}
		
		return $r;
	}
	
	public function in010()
	{
		list($fn016, $fn012) = $this->campos(['fn016', 'fn012']);
		
		$r = null;
		if (is_numeric($fn016) && !empty((int) $fn012)) {
			$r = $this->roundUp((($fn016 / $fn012) * 100), 1);
		}
		return $r;
	}
	
	public function in020()
	{
		list($ie019, $ie017) = $this->campos(['ie019', 'ie017']);
		
		$r = null;
		if (is_numeric($ie019) && !empty((int) $ie017)) {
			$r = $this->roundUp((($ie019 / $ie017) * 100), 1);
		}
		
		return $r;
	}
	
	public function in021()
	{
		list($ie024, $ie017) = $this->campos(['ie024', 'ie017']);
		
		$r = null;
		if (is_numeric($ie024) && !empty((int) $ie017)) {
			$r = $this->roundUp((($ie024 / $ie017) * 100), 1);
		}
		
		return $r;
	}
	
	public function in025()
	{
		list($ie044, $ie032) = $this->campos(['ie044', 'ie032']);
		
		$r = null;
		if (is_numeric($ie044) && !empty((int) $ie032)) {
			$r = $this->roundUp((($ie044 / $ie032) * 100), 1);
		}
		
		return $r;
	}
	
	public function in026()
	{
		list($ie034, $ie032) = $this->campos(['ie034', 'ie032']);
		
		$r = null;
		if (is_numeric($ie034) && !empty((int) $ie032)) {
			$r = $this->roundUp((($ie034 / $ie032) * 100), 1);
		}
		
		return $r;
	}
	
	public function in027()
	{
		list($ie35, $ie032) = $this->campos(['ie035', 'ie032']);
		
		$r = null;
		if (is_numeric($ie35) && !empty((int) $ie032)) {
			$r = $this->roundUp((($ie35 / $ie032) * 100), 1);
		}
		
		return $r;
	}
	
	public function in028()
	{
		list($ie36, $ie032) = $this->campos(['ie036', 'ie032']);
		
		$r = null;
		if (is_numeric($ie36) && !empty((int) $ie032)) {
			$r = $this->roundUp((($ie36 / $ie032) * 100), 1);
		}
		
		return $r;
	}
	
	public function in029()
	{
		list($ie33, $ie032) = $this->campos(['ie033', 'ie032']);
		
		$r = null;
		if (is_numeric($ie33) && !empty((int) $ie032)) {
			$r = $this->roundUp((($ie33 / $ie032) * 100), 1);
		}
		
		return $r;
	}
	
	public function in035()
	{
		$ie058 = $this->getSomatorio($this->campo('bacias'), 'ie058');
		$ge002 = $this->campo('ge002');
		
		$r = null;
		if (is_numeric($ie058) && !empty((int) $ge002)) {
			$r = $this->roundUp(($ie058 / ($ge002 * 1000)));
		}

		return $r;
	}
	
	public function in037()
	{
		list($ie021, $ie019) = $this->campos(['ie021', 'ie019']);
		
		$r = null;
		if (is_numeric($ie021) && !empty((int) $ie019)) {
			$r = $this->roundUp(($ie021 / $ie019), 1);
		}
		
		return $r;
	}
	
	public function in040()
	{
		list($ri013, $ge008) = $this->campos(['ri013', 'ge008']);
		
		$r = null;
		if (is_numeric($ri013) && !empty((int) $ge008)) {
			$r = $this->roundUp(($ri013 / $ge008) * 100, 1);
		}
		
		return $r;
	}
	
	public function in041()
	{
		list($ri029, $ri067, $ge006) = $this->campos(['ri029', 'ri067', 'ge006']);
		
		$r = null;
		if (is_numeric($ri029) && is_numeric($ri067) && !empty((int) $ge006)) {
			$r = $this->roundUp(((($ri029 + $ri067) / $ge006) * 100), 1);
		}
		
		return $r;
	}
}