<?php 

namespace projeto\pdf;

use Yii;
use yii\helpers\FileHelper;
use kartik\mpdf\Pdf;

class Oficio extends Pdf
{
	public $mode = Pdf::MODE_UTF8;
	public $destination = Pdf::DEST_FILE;
	public $idMalaDireta;

	public function __construct(array $params=[])
	{
		parent::__construct($params);

		if (empty($this->idMalaDireta)) {
			throw new yii\base\InvalidParamException('Faltando $idMalaDireta');
		}

		$dir = Yii::getAlias("@runtime/mala-direta-oficios/{$this->idMalaDireta}");
		if (!is_dir($dir)) {
			FileHelper::createDirectory($dir);
		}
	}
}
