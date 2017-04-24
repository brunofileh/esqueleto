<?php

namespace projeto\base;

class Widget extends \yii\base\Widget
{
	use \projeto\Atalhos;
	public function init()
	{
		parent::init();
		$this->configAtalhos();
	}
}