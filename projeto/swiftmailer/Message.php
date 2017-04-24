<?php 

namespace projeto\swiftmailer;

class Message extends \yii\swiftmailer\Message
{
	public function setSubject($subject)
	{
		if (YII_ENV !== 'prod') {
			$subject = '**'. YII_ENV ."** {$subject}";
		}
		
		parent::setSubject($subject);
		return $this;
	}
}