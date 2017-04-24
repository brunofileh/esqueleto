<?php 

namespace projeto\pdf;

use Yii;
use kartik\mpdf\Pdf;
use projeto\Util;
use app\models\TabModeloDocs;
use yii\web\NotFoundHttpException;
use projeto\web\View;

class ModeloDoc extends Pdf
{
	use \projeto\Atalhos;

	public $mode = Pdf::MODE_UTF8;
	public $orientation = Pdf::ORIENT_PORTRAIT;
	public $view = null;
	public $cabecalho = null;
	public $rodape = null;

	public function __construct(array $params=[])
	{
		parent::__construct($params);
		
		$this->view = new View();
		
		$tplCabecalho = '';
		if (!empty($this->cabecalho) && $this->cabecalho !== 'cabecalho-sem') {
			$tplCabecalho = $this->view->render("@app/views/pdf/view/{$this->cabecalho}");
		}
		
		$tplRodape = '';
		if (!empty($this->rodape) && $this->rodape !== 'rodape-sem') {
			$tplRodape = $this->view->render("@app/views/pdf/view/{$this->rodape}");
		}

		$this->content = $tplCabecalho . $this->content;
		
		if (!empty($tplRodape)) {
			$this->methods = ['SetHTMLFooter' => $tplRodape];
		}

		if (!is_dir(Yii::getAlias("@runtime/tmp/"))) {
			mkdir(Yii::getAlias("@runtime/tmp/"), 0777);
		}
	}
	
	public static function gerarPdf($sglId, array $params=[], array $variaveis=[])
	{
		$model = null;
		if (is_numeric($sglId)) {
			$model = TabModeloDocs::findOne($sglId);
		}
		else {
			$model = TabModeloDocs::find()->where(['sgl_id' => $sglId])->one();
		}
		
		if ($model === null) {
			throw new NotFoundHttpException('A página solicitada não existe.');
		}

		$filename = $params['filename'];
		unset($params['filename']);
		
		$self = new static;
		
		$defaultParams = [

			'destination' => $params['destination'],
			'filename' => $filename,
			'cabecalho' => Util::attrVal($model->cabecalho_fk),
			'rodape' => Util::attrVal($model->rodape_fk),
			'content' => $self->view->render('@app/views/modelo-docs/print', [
				'html' => Util::substituirVariaveis($model->txt_conteudo, $variaveis),
			]),
		];
		
		return (new ModeloDoc(array_merge($defaultParams, $params)))->render(); 
	}
	
	public static function gerarEmail($sglId, array $variaveis=[])
	{
		$model = null;
		if (is_numeric($sglId)) {
			$model = TabModeloDocs::findOne($sglId);
		}
		else {
			$model = TabModeloDocs::find()->where(['sgl_id' => $sglId])->one();
		}
		
		if ($model === null) {
			throw new NotFoundHttpException('A página solicitada não existe.');
		}
		
		return Util::substituirVariaveis($model->txt_conteudo, $variaveis);
	}
}
