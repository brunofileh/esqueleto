<?php

namespace projeto\db;

use app\modules\drenagem\models\TabCampoMultiOpcoes;
use app\models\VisGlossarios;
use app\models\VisAtributosValores;
use projeto\Util;

class ActiveRecord extends \yii\db\ActiveRecord {

	use \projeto\Atalhos;

	// timestamps
	const AUDIT_CAMPO_INCLUSAO = 'dt_inclusao';
	const AUDIT_CAMPO_ALTERACAO = 'dt_alteracao';
	// usuário última alteração
	const AUDIT_CAMPO_USUARIO = 'txt_login_inclusao';

	public function init() {

		parent::init();

		$this->configAtalhos();
	}

	public static function findOneAsArray(array $where = []) {
		return static::find()->where($where)->asArray()->one();
	}

	public static function findAllAsArray(array $where = []) {
		return static::find()->where($where)->orderBy('')->asArray()->all();
	}

	public function afterFind() {
		parent::afterFind();

		if ($this->getTableSchema()->columns) {
			foreach ($this->getTableSchema()->columns as $key => $value) {

				if ($value->type == 'date' && $this->$key) {
					$this->$key = self::formataDataDoBanco($this->$key);
				}
			}
		}
		return true;
	}

	public function beforeSave($insert) {

		if ($this->getTableSchema()->columns) {
			foreach ($this->getTableSchema()->columns as $key => $value) {

				if ($value->type == 'date' && $this->$key) {
					$this->$key = self::formataDataParaBanco($this->$key);
				}
			}
		}
		return parent::beforeSave($insert);
	}

	public static function decimalFormatForBank($string) {
		if ($string != ",") {
			$string = str_replace(',', '.', str_replace('.', '', $string));
		} else {
			$string = null;
		}

		return $string;
	}

	public static function decimalFormatToBank($string) {
		if ($string) {
			$string = number_format($string, 2, ',', '.');
		}

		return $string;
	}

	public static function formataDataParaBanco($data) {
		$date = explode('/', $data);

		if ($date[0]) {
			$date = $date[2] . '-' . $date[1] . '-' . $date[0];
		} else {
			$date = null;
		}
		return $date;
	}

	public static function formataDataDoBanco($data) {
		$date = explode('-', $data);

		if ($date[0]) {
			$date = $date[2] . '/' . $date[1] . '/' . $date[0];
		} else {
			$date = null;
		}
		return $date;
	}

}
