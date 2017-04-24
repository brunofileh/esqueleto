<?php

namespace projeto\web;

class View extends \yii\web\View
{
	use \projeto\Atalhos;

	public function init()
	{
		parent::init();
		// trait de atalhos \projeto\Atalhos
		$this->configAtalhos();
	}
}