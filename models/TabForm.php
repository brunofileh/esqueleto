<?php

namespace app\models;

use Yii;

class TabForm extends base\TabForm
{
	public function attributeLabels()
	{
		return [
			'cod_form' => 'Código',
			'cod_tipo_servico' => 'Serviço',
			'dsc_form' => 'Descrição',
			'dsc_det_form' => 'Descrição detalhada',
			'sgl_form' => 'Sigla',
			'dte_inclusao' => 'Dte Inclusao',
            'dte_alteracao' => 'Dte Alteracao',
            'dte_exclusao' => 'Dte Exclusao',
            'txt_login_inclusao' => 'Txt Login Inclusao',
		];
	}
	
	 /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod_tipo_servico', 'dsc_form', 'sgl_form'], 'required'],
            [['cod_tipo_servico'], 'integer'],
            [['dsc_det_form'], 'string'],
            [['dsc_form'], 'string', 'max' => 120],
            [['sgl_form'], 'string', 'max' => 2],
			[['dte_inclusao', 'dte_alteracao', 'dte_exclusao'], 'safe'],
			[['txt_login_inclusao'], 'string', 'max' => 150],
        ];
    }
	
}