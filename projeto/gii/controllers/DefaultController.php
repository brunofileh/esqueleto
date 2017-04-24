<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace projeto\gii\controllers;

use Yii;

class DefaultController extends \yii\gii\controllers\DefaultController
{

	public function actionView($id)
	{

		$generator	 = $this->loadGenerator($id);
		$params		 = ['generator' => $generator, 'id' => $id];

		$preview	 = Yii::$app->request->post('preview');
		$generate	 = Yii::$app->request->post('generate');
		$answers	 = Yii::$app->request->post('answers');

		if ($preview !== null || $generate !== null) {
			if ($generator->validate()) {

				$generator->saveStickyAttributes();
				$files = $generator->generate();
				
				if(array_key_exists('generate', Yii::$app->request->post()) && $params['id']=='model'){
					if ($generator->glossario) {
						$generator->geraGlossario();
						\app\models\VisGlossarios::atualizar();
					}
				}
				
				if ($generate !== null && !empty($answers)) {
					$params['hasError']	 = !$generator->save($files, (array) $answers, $results);
					$params['results']	 = $results;
				} else {
					$params['files']	 = $files;
					$params['answers']	 = $answers;
				}
			}
		}

		return $this->render('view', $params);

	}

}
