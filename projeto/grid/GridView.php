<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace projeto\grid;

use Yii;
use yii\helpers\Html;
use projeto\web\ActiveForm;


/**
 * The GridView widget is used to display data in a grid.
 *
 * It provides features like [[sorter|sorting]], [[pager|paging]] and also [[filterModel|filtering]] the data.
 *
 * A basic usage looks like the following:
 *
 * ```php
 * <?= GridView::widget([
 *     'dataProvider' => $dataProvider,
 *     'columns' => [
 *         'id',
 *         'name',
 *         'created_at:datetime',
 *         // ...
 *     ],
 * ]) ?>
 * ```
 *
 * The columns of the grid table are configured in terms of [[Column]] classes,
 * which are configured via [[columns]].
 *
 * The look and feel of a grid view can be customized using the large amount of properties.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GridView extends \yii\grid\GridView {
	
	public $tableOptions = ['class' => 'table table-striped table-bordered table-hover'];
	
	/**
	 * Initializes the grid view.
	 * This method will initialize required property values and instantiate [[columns]] objects.
	 */
	public $avisosErros = [];

	public function init() {
		parent::init();
	}

	protected function initColumns() {
		if (empty($this->columns)) {
			$this->guessColumns();
		}

		foreach ($this->columns as $i => $column) {
			if (is_string($column)) {
				$column = $this->createDataColumn($column);
			} else {

				$column = Yii::createObject(array_merge([
						'class' => $this->dataColumnClass ? : DataColumn::className(),
						'grid' => $this,
							], $column));
			}
			if (!$column->visible) {
				unset($this->columns[$i]);
				continue;
			}

			$this->columns[$i] = $column;
		}
	}

	public function renderTableRow($model, $key, $index) {
		$cells = [];
		/* @var $column Column */
		foreach ($this->columns as $column) {

			if (isset($column->attribute) && $column->attribute == 'linhaErro') {
				
				$cells[] = $this->geraErrosLinha($model, $index);
			} else {

				$cells[] = $column->renderDataCell($model, $key, $index);
			}
		}
		
		if ($this->rowOptions instanceof Closure) {
			$options = call_user_func($this->rowOptions, $model, $key, $index, $this);
		} else {
			$options = $this->rowOptions;
		}
		$options['data-key'] = is_array($key) ? json_encode($key) : (string) $key;

		return Html::tag('tr', implode('', $cells), $options);
	}

	public function geraErrosLinha($model, $index) {

		$cell = '';
		$avisos = $model->classeAvisos->calcular();
		if ($avisos) {
			$tipo = 'A';
			$titulo = "Avisos da linha " . ($index + 1);
			$cell = '<td><div><a style="color:#FFF" data-target="#linha-' . $index . '-modal" data-toggle="modal" href="javascript://;"><div style="width: 12px; float: left; margin-left: 3px;"><i style="color:#9C6500" class="fa fa-exclamation"></i></div></a></div></td>';
		}
		$erros = $model->classeErros->calcular();
		
		if ($erros) {
			$tipo = 'E';
			$titulo = "Erros da linha " . ($index + 1);
			$cell = '<td><div><a style="color:#FFF" data-target="#linha-' . $index . '-modal" data-toggle="modal" href="javascript://;"><div style="width:15px; float: left;"><i style="color:red" class="fa fa-remove"></i></div></a></div></td>';
		}
		if ($cell) {
			$model->alertas['erros'] = $erros;
			$model->alertas['avisos'] = $avisos;
			$html = ActiveForm::getModalsAvisosErros($tipo, $model);
			
			$modalId = "linha-" . $index."-modal";
			ActiveForm::gerarModal($modalId, $titulo, $html);
		}
		return ($cell) ? $cell : '<td><span style="border-color:#fff"><div style="width:10px"><i style="color:green" class="fa fa-check"></i></div></span></td>';
	}

}
