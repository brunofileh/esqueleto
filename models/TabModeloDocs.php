<?php

namespace app\models;

use projeto\Util;

class TabModeloDocs extends base\TabModeloDocs
{
	public $dsc_cabecalho;
	public $dsc_rodape;
	public $dsc_finalidade;
	public $dsc_tipo_modelo_documento;
	
	public $camposChaveAttrVlr = [
		'cabecalho_fk', 
		'rodape_fk', 
		'finalidade_fk', 
		'tipo_modelo_documento_fk',
	];
	
	public function rules()
	{
		$rules = parent::rules();
		
		// remove regra de validação do cabeçalho
		$rules[0] = [['finalidade_fk', 'sgl_id', 'tipo_modelo_documento_fk', 'txt_conteudo'], 'required'];
		
		// tira as regras para 'integer' por conta dos campos self::$camposChaveAttrVlr
		$rules[1] = [['modulo_fk'], 'integer'];
		
		// ...
		$rules[] = [['finalidade_fk', 'rodape_fk', 'cabecalho_fk', 'tipo_modelo_documento_fk'], 'safe'];
		
		// altera a regra de validação do cabeçalho
		$rules[] = [['cabecalho_fk'], 'required', 'when' => function ($model) {
			return Util::attrVal($model->tipo_modelo_documento_fk) == 'tipo-modelo-documento-pdf';
		}, 'whenClient' => 'function (attribute, value) {
			return projeto.util.attrVal($("#tabmodelodocs-tipo_modelo_documento_fk").find("option:selected")) == "tipo-modelo-documento-pdf";
		}'];
		
		// altera a regra de validação do rodapé
		$rules[] = [['rodape_fk'], 'required', 'when' => function ($model) {
			return Util::attrVal($model->tipo_modelo_documento_fk) == 'tipo-modelo-documento-pdf';
		}, 'whenClient' => 'function (attribute, value) {
			return projeto.util.attrVal($("#tabmodelodocs-tipo_modelo_documento_fk").find("option:selected")) == "tipo-modelo-documento-pdf";
		}'];
		
		return $rules;
	}
	
	public function attributeLabels()
	{
		return [
			'cod_modelo_doc' => 'Código',
			'modulo_fk' => 'Cód. Módulo',
			'cabecalho_fk' => 'Cabeçalho',
			'dsc_cabecalho' => 'Cabeçalho',
			'rodape_fk' => 'Rodapé',
			'dsc_rodape' => 'Rodapé',
			'finalidade_fk' => 'Finalidade',
			'dsc_finalidade' => 'Finalidade',
			'tipo_modelo_documento_fk' => 'Tipo do documento',
			'dsc_tipo_modelo_documento' => 'Tipo do documento',
			'sgl_id' => 'Identificador',
			'txt_descricao' => 'Descrição',
			'txt_conteudo' => 'Conteúdo',
			'dte_inclusao' => 'Dte Inclusao',
			'dte_alteracao' => 'Data',
			'dte_exclusao' => 'Dte Exclusao',
			'txt_login_inclusao' => 'Login Inclusao',
		];
	}
	
	public function beforeSave($insert)
	{
		$tipoModeloDoc = Util::attrVal($this->tipo_modelo_documento_fk);
		
		if (parent::beforeSave($insert)) {
			if ($tipoModeloDoc == 'tipo-modelo-documento-email') {
				$this->cabecalho_fk = VisAtributosValores::getTupla('cabecalho-modelo-documento', 'cabecalho-sem');
				$this->rodape_fk = VisAtributosValores::getTupla('rodape-modelo-documento', 'rodape-sem');
			}
			return true;
		}
		
		return false;
	}
	
	public function afterFind()
	{
		parent::afterFind();
		static::carregarDescricaoAtributoValor([
			'cabecalho_fk' => 'dsc_cabecalho',
			'rodape_fk' => 'dsc_rodape',
			'finalidade_fk' => 'dsc_finalidade',
			'tipo_modelo_documento_fk' => 'dsc_tipo_modelo_documento',
		]);
	}
}