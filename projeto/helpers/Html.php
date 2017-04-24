<?php

namespace projeto\helpers;

use \yii\helpers\ArrayHelper;

class Html extends \yii\helpers\Html
{
	public static function icon($type, $text='', array $options=[])
	{
		$class = "glyphicon glyphicon-$type";
		if (isset($options['class'])) {
			$class .= " {$options['class']}";
		}
		return static::tag('i', '', ArrayHelper::merge($options, [
			'class' => $class,
		])) . ($text ? " $text" : '');
	}
	
	public static function ficon($type, $text='', array $options=[])
	{
		$class = "fa fa-$type";
		if (isset($options['class'])) {
			$class .= " {$options['class']}";
		}
		return static::tag('i', '', ArrayHelper::merge($options, [
			'class' => $class,
		])) . ($text ? " $text" : '');
	}
}