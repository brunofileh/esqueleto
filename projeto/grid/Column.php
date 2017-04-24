<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace projeto\grid;

/**
 * Column is the base class of all [[GridView]] column classes.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Column extends \yii\grid\Column {

	protected function renderDataCellContent($model, $key, $index) {

		if ($this->content !== null) {
			$cell = call_user_func($this->content, $model, $key, $index, $this);

			return $cell;
		} else {

			return $this->grid->emptyCell;
		}
	}

	protected function verificaErroAviso($cell, $key, $model) {
		$cellErroAviso = null;

		if ($model->classeAvisos->calcular()) {
			$cellErroAviso = '<div><a style="color:#FFF" data-target="#modal-campo-' . $this->attribute . '" data-toggle="modal" href="javascript://;"><div style="width: 12px; float: left; margin-left: 3px;"><i style="color:#9C6500" class="fa fa-exclamation"></i></div></a>' . $cell . '</div>';
		}

		if ($model->classeErros->calcular()) {
			$cellErroAviso = '<div><a style="color:#FFF" data-target="#modal-campo-' . $this->attribute . '" data-toggle="modal" href="javascript://;"><div style="width:15px; float: left;"><i style="color:red" class="fa fa-remove"></i></div></a>' . $cell . '</div>';
		}
		return ($cellErroAviso) ? $cellErroAviso : $cell;
	}

}
