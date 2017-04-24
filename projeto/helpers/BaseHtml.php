<?php

namespace projeto\helpers;

use Yii;
use yii\base\Model;
use \yii\helpers\ArrayHelper;

class BaseHtml extends \yii\helpers\BaseHtml {

	public static function errorSummary($models, $options = []) {
		$header = isset($options['header']) ? $options['header'] : Yii::t('yii', 'Please fix the following errors:');
		$footer = ArrayHelper::remove($options, 'footer', '');
		$encode = ArrayHelper::remove($options, 'encode', true);
		unset($options['header']);

		$lines = [];
		if (!is_array($models)) {
			$models = [$models];
		}
		foreach ($models as $model) {
			/* @var $model Model */
			foreach ($model->getFirstErrors() as $error) {
				$lines[] = $encode ? Html::encode($error) : $error;
			}
		}

		if (empty($lines)) {
			// still render the placeholder for client-side validation use
			$content = '<ul></ul>';
			$options['style'] = isset($options['style']) ? rtrim($options['style'], ';') . '; display:none' : 'display:none';
		} else {
			$content = '<ul><li>' . implode("</li>\n<li>", $lines) . '</li></ul>';
		}
		return Html::tag('span', $header . $content . $footer, $options);
	}

}
