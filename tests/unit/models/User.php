<?php

namespace tests\unit\models;

class User extends \yii\db\ActiveRecord
{
	public static function tablename()
	{
		return 'tb_usuario';
	}
}