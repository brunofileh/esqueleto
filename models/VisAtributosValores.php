<?php

namespace app\models;

use Yii;
use projeto\Util;

/**
 * This is the model class for table "public.vis_atributos_valores".
 *
 * @property integer $cod_atributos
 * @property integer $cod_atributos_valores
 * @property string $descr_atributo
 * @property string $sgl_chave
 * @property string $sgl_valor
 * @property string $dsc_descricao
 */
class VisAtributosValores extends \projeto\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'public.vis_atributos_valores';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['cod_atributos', 'cod_atributos_valores'], 'integer'],
			[['descr_atributo', 'sgl_chave', 'sgl_valor'], 'string'],
			[['dsc_descricao'], 'string', 'max' => 200]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'cod_atributos' => 'Cod Atributos',
			'cod_atributos_valores' => 'Cod Atributos Valores',
			'descr_atributo' => 'Descr Atributo',
			'sgl_chave' => 'Sgl Chave',
			'sgl_valor' => 'Sgl Valor',
			'dsc_descricao' => 'Dsc Descricao',
		];
	}

	/**
	 * @inheritdoc
	 * @return VisAtributosValoresQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new VisAtributosValoresQuery(get_called_class());
	}

	public static function getOpcoes($chaveGrupo, $srv=null, $ordem='sgl_valor')
	{
		$getData = function ($chaveGrupo, $srv, $ordem) {
			$retorno = [];
			$q = static::find()->where(['sgl_chave' => $chaveGrupo]);
			
			if (is_string($srv)) {
				$q->andWhere(['SUBSTRING(dsc_descricao FROM 2 FOR 2)' => $srv]);
			}
			
			if (is_string($ordem)) {
				$q->orderBy($ordem);
			}
			
			$r = $q->asArray()->all();
			
			if ($r === []) {
				throw new \Exception("Opções não encontradas para a chave: $chaveGrupo");
			}
			
			foreach ($r as $item) {
				$retorno["{$item['cod_atributos_valores']}@{$item['sgl_valor']}"] = $item['dsc_descricao'];
			}
			
			return $retorno;
		};
		
		if (\Yii::$app->params['habilitar-cache-global']) {
			$cacheKey = ['tabela-atributo-valor', $chaveGrupo, $srv, $ordem];
			if (($data = \Yii::$app->cache->get($cacheKey)) === false) {
				$data = $getData($chaveGrupo, $srv, $ordem);
				\Yii::$app->cache->set($cacheKey, $data);
			}
		}
		else {
			$data = $getData($chaveGrupo, $srv, $ordem);
		}

		return $data;
	}

	public static function getTupla($chave, $valor)
	{
		$r = static::find()
			->where([
				'sgl_chave' => $chave, 
				'sgl_valor' => $valor,
			])
			->asArray()
			->one()
		;

		if ($r === null) {
			$msg = "Registro não encontrado para: sgl_chave = '{$chave}', sgl_valor = '{$valor}'";
			throw new \yii\base\NotSupportedException($msg);
		}

		return "{$r['cod_atributos_valores']}@{$r['sgl_valor']}";
	}
	
	public static function getIdAtributo($chave, $valor, $returnRow=false)
	{
		$r = static::find()
			->where([
				'sgl_chave' => $chave, 
				'sgl_valor' => $valor,
			])
			->asArray()
			->one()
		;

		if ($r === null) {
			$msg = "Registro não encontrado para: sgl_chave = '{$chave}', sgl_valor = '{$valor}'";
			throw new \yii\base\NotSupportedException($msg);
		}

		return $returnRow ? $r : $r['cod_atributos_valores'];
	}
	
	public static function getDescrOpcao($chaveValor)
	{
		$chave = $valor = [];
		if (!is_array($chaveValor)) {
			$chaveValor = [$chaveValor];
		}
		
		foreach ($chaveValor as $item) {
			$chave[] = Util::attrId($item);
			$valor[] = Util::attrVal($item);
		}
		
		return array_map(function ($item) {
			return $item['dsc_descricao'];
		}, static::find()->where(['cod_atributos_valores' => $chave, 'sgl_valor' => $valor])->asArray()->all());
	}
	
	public static function atualizar()
	{
		$command = 'REFRESH MATERIALIZED VIEW ' . static::tableName();
		return Yii::$app->db->createCommand($command)->execute();
	}
}
