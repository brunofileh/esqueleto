<?php

namespace projeto;

use app\models\VisAtributosValores;

/**
 * Classe de utilitários para o sistema
 */
class Util
{
	/**
	 * Debug function
	 * \projeto\Util::d($var);
	 */
	public static function d($var, $caller=null)
	{
		if (!isset($caller)) {
			$trace = debug_backtrace(1);
			$caller = array_shift($trace);
		}
		echo '<code>File: ' . $caller['file'] . ' / Line: ' . $caller['line'] . '</code>';
		echo '<pre>';
		\yii\helpers\VarDumper::dump($var, 10, true);
		echo '</pre>';
	}
	/**
	 * Debug function with die() after
	 * \projeto\Util::dd($var);
	 */
	public static function dd($var)
	{
		$trace = debug_backtrace(1);
		$caller = array_shift($trace);
		static::d($var, $caller);
		die();
	}
	/**
	 * Retorna o ID de um campo da tabela tab_atributos_valores
	 * \projeto\Util::attrId('25@DN') -> 25
	 */
	public static function attrId($vlr)
	{
		if (!$vlr) {
			return '';
		}
		
		return explode('@', $vlr)[0];
	}
	/**
	 * Retorna o VALOR de um campo da tabela tab_atributos_valores
	 * \projeto\Util::attrId('25@DN') -> DN
	 */
	public static function attrVal($vlr)
	{
		if (!$vlr) {
			return '';
		}
		try {
			return explode('@', $vlr)[1];
		}
		catch (\Exception $e) {
			throw new \Exception('$vlr ('. $vlr .') precisa ser do tipo string. Obtido: ' . gettype($vlr));
		}
	}

	public static function getClientIP()
	{
		$ipaddress = '';
		
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		elseif(isset($_SERVER['HTTP_X_FORWARDED'])) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		}
		elseif(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		}
		elseif(isset($_SERVER['HTTP_FORWARDED'])) {
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		}
		elseif(isset($_SERVER['REMOTE_ADDR'])) {
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		}
		
		return $ipaddress;
	}

	public static function humanFileSize($size, $unit='') {
		if((!$unit && $size >= 1<<30) || $unit == "GB") {
			return number_format($size/(1<<30),2) . "GB";
		}
		if((!$unit && $size >= 1<<20) || $unit == "MB") {
			return number_format($size/(1<<20),2) . "MB";
		}
		if((!$unit && $size >= 1<<10) || $unit == "KB") {
			return number_format($size/(1<<10),2) . "KB";
		}

		return number_format($size)." bytes";
	}

	public static function slug($string) {
		$string = trim($string);
		$string = preg_replace(['/[^-a-zA-Z0-9\s]/', '/[\s]/'], ['', '-'], $string);
		$string = strtolower($string);
		return $string;
	}

	public static function decimalFormatForBank($string) {
		if ($string != ",") {
            if (strpos($string, ',') === false) {
                return $string;
            } else {
                $string = str_replace(',', '.', str_replace('.', '', $string));
            }
		} else {
			$string = null;
		}

		return $string;
	}
	
	public static function decimalFormatToBank($string) {
		if ($string) {
			 $string = number_format($string, 2, ',', '.');
		}

		return $string;
	}
	
	public static function exibirValorInfo($model, $codInfo, $sglTipoApresentacao) 
	{
		$r = '';
		$vlr = $model->$codInfo;
		
		$formatter = \Yii::$app->formatter;
		$formatter->nullDisplay = '-';

		switch($sglTipoApresentacao) {
			case 'input:money' :
				$r = str_replace('R$', '', $vlr);
				break;
			
			case 'input:numeric' :
				if (is_numeric($vlr)) {
					$r = $formatter->asDecimal($vlr);
				}
				else {
					$r = $vlr;
				}
				break;
			
			case 'checkboxlist' :
				$s = VisAtributosValores::getDescrOpcao($vlr);
				$r = '- ';
				if (!empty($s)) {
					$r .= implode('<br>- ', $s);
				}
				break;

			case 'dropdownlist' :
			case 'radiolist' :
				$r = '-';
				if ($vlr && !empty(($s = VisAtributosValores::getDescrOpcao($vlr)))) {
					$r = $s[0];
				}
				break;

			default :
				$r = $vlr;
		}

		return $r;
	}
	
	private static function folderToZip($folder, &$zipFile, $exclusiveLength)
	{ 
		$handle = opendir($folder); 
		while (false !== $f = readdir($handle)) { 
			if ($f != '.' && $f != '..') { 
				$filePath = "$folder/$f"; 
				$localPath = substr($filePath, $exclusiveLength); 
				if (is_file($filePath)) { 
					$zipFile->addFile($filePath, $localPath); 
				}
				elseif (is_dir($filePath)) { 
					$zipFile->addEmptyDir($localPath); 
					self::folderToZip($filePath, $zipFile, $exclusiveLength); 
				}
			}
		} 
		closedir($handle); 
	}

	public static function zipDir($sourcePath, $outZipPath) 
	{ 
		$z = new \ZipArchive(); 
		$z->open($outZipPath, \ZipArchive::CREATE); 
		self::folderToZip($sourcePath, $z, strlen("$sourcePath/")); 
		$z->close(); 
	} 
	
	public static function retiraFormatoMilhar($vlr)
	{
		if (!$vlr) {
			return '';
		} else {
			if(strpos($vlr, ',')==true){
				return str_replace(',', '.', str_replace('.', '', $vlr));
			} else {
                return $vlr;
            }
		}
	}
	
	public static function retiraCaracter($vlr)
	{
		if (!$vlr) {
			return '';
		} else {
			return str_replace(['-', '_', '(', ')'], '', $vlr);
		}
	}
	/**
	 * Data por extenso
	 * @param null|int $timestamp
	 *	
	 * @return string
	 */
	public static function humanDate($timestamp=null, $mostrarDiaDaSemana=false)
	{
		if (is_null($timestamp)) {
			$timestamp = time();
		}
		
		$meses = [
			1 => 'Janeiro', 
			2 => 'Fevereiro', 
			3 => 'Março', 
			4 => 'Abril', 
			5 => 'Maio', 
			6 => 'Junho', 
			7 => 'Julho', 
			8 => 'Agosto', 
			9 => 'Setembro', 
			10 => 'Outubro', 
			11 => 'Novembro', 
			12 => 'Dezembro',
		];
		
		$diasdasemana = [
			0 => 'Domingo',
			1 => 'Segunda-Feira',
			2 => 'Terça-Feira',
			3 => 'Quarta-Feira',
			4 => 'Quinta-Feira',
			5 => 'Sexta-Feira',
			6 => 'Sábado',
		];
		
		$data = getdate($timestamp);
		
		$dia = $data["mday"];
		$mes = $data["mon"];
		$nomemes = $meses[$mes];
		$ano = $data["year"];
		$diadasemana = $data["wday"];
		$nomediadasemana = $diasdasemana[$diadasemana];

		if ($mostrarDiaDaSemana) {
			return "$nomediadasemana, $dia de $nomemes de $ano";
		}
		else {
			return "$dia de $nomemes de $ano";
		}
	}
	
	public static function substituirVariaveis($template, array $variaveis)
	{
		foreach ($variaveis as $var => $value) {
			$template = str_replace('{' . $var . '}', $value, $template);
		}
		
		return $template;
	}
	
	public static function mostrarCampoOutrosNaImpressao($codInfo, $model) {
		if (!isset($model->$codInfo) || !is_array($model->$codInfo)) {
			return false;
		}

		$opts = array_map(function ($item) {
			return static::attrVal($item);
		}, $model->$codInfo);

		return in_array(99, $opts);
	}
	
	public static function formataDataParaBanco($data){
		$date = explode('/', $data);

		if ($date[0]) {
			$date = $date[2] . '-' . $date[1] . '-' . $date[0];
		} else {
			$date = null;
		}
		return $date;
	}
}
 