<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use \yii\helpers\ArrayHelper;

class TabModeloDocsSearch extends base\TabModeloDocsSearch
{
	public function search($params)
    {
        $query = TabModeloDocsSearch::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['txt_descricao' => SORT_ASC]]
        ]);

        $this->load($params);

        $query->andFilterWhere([
            $this->tableName() . '.cod_modelo_doc' => $this->cod_modelo_doc,
            $this->tableName() . '.modulo_fk' => $this->modulo_fk,
			$this->tableName() . '.cabecalho_fk' => $this->cabecalho_fk, 
			$this->tableName() . '.rodape_fk' => $this->rodape_fk,
			$this->tableName() . '.tipo_modelo_documento_fk' => $this->tipo_modelo_documento_fk, 
			$this->tableName() . '.finalidade_fk' => $this->finalidade_fk,
            $this->tableName() . '.dte_inclusao' => $this->dte_inclusao,
            $this->tableName() . '.dte_alteracao' => $this->dte_alteracao,
            $this->tableName() . '.dte_exclusao' => $this->dte_exclusao,
        ]);

        $query->andFilterWhere(['ilike', $this->tableName() . '.sgl_id', $this->sgl_id])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_descricao', $this->txt_descricao])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_conteudo', $this->txt_conteudo])
            ->andFilterWhere(['ilike', $this->tableName() . '.txt_login_inclusao', $this->txt_login_inclusao]);

		$query->andWhere($this->tableName().'.dte_exclusao IS NULL');
		$query->orderBy('dte_alteracao desc');
		$dataProvider->sort->defaultOrder = 'dte_alteracao';
		$dataProvider->sort->attributes['dte_alteracao'] = [
			'asc' => ['dte_alteracao' => SORT_ASC],
			'desc' => ['dte_alteracao' => SORT_DESC],
		];
		
        return $dataProvider;
    }
	
	/**
	 * @param integer $cod_modulo
	 * @param integer $tipo - tipo documento
	 * @return ArrayHelper
	 */
	public static function getAllDocs($cod_modulo)
	{		
		$dados = TabModeloDocsSearch::find()
			->where(['modulo_fk'=>$cod_modulo])
			->andWhere('dte_exclusao IS NULL')
			->orderBy('txt_descricao')->asArray()->all();
	
		$arr = ArrayHelper::map($dados, 'sgl_id', 'txt_descricao');
		return $arr;
	}
}
