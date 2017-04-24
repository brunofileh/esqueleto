<?php 

namespace projeto\pdf;

use Yii;
use kartik\mpdf\Pdf;

class PrintForm extends Pdf
{
	use \projeto\Atalhos;

	public $mode = Pdf::MODE_UTF8;
	public $orientation = Pdf::ORIENT_LANDSCAPE;
	public $view = null;
	public $titulo = '';
	public $subtitulo = '';
	public $subtitulo2 = '';
	public $ano = '';

	public function __construct(array $params=[])
	{
		parent::__construct($params);
		
		$topo = $this->view->render('@app/views/pdf/view/header-forms', [
			'titulo' => $params['titulo'],
			'subtitulo' => $params['subtitulo'],
			'subtitulo2' => $params['subtitulo2'],
			'ano' => $params['ano'],
		]);
		
		$this->api->showImageErrors = true;

		if(YII_ENV != 'prod')
		{
			$this->filename = strtoupper(YII_ENV).'-'.$this->filename;
			$descAmbiente = "<span style='color: #FF0000; font-size: 14px;'> ** ".strtoupper(YII_ENV)." ** </span>";
		}

		$this->content = $topo . $this->content;
		$this->methods = [
			'SetHeader' => "SNIS - Sistema Nacional de Informações sobre Saneamento || {$params['subtitulo']} ({$params['ano']})  ".$descAmbiente,
			'SetFooter' => 'Ministério das Cidades | Página {PAGENO} de {nbpg} | Emitido em: {DATE j/m/Y}',
		];

		if (!is_dir(Yii::getAlias("@runtime/tmp/"))) {
			mkdir(Yii::getAlias("@runtime/tmp/"), 0755);
		}
	}
}