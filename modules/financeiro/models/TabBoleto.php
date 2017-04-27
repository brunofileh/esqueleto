<?php

namespace app\modules\financeiro\models;

use Yii;

/**
 * This is the model class for table "financeiro.tab_boleto".
 *
 * @property integer $cod_boleto
 * @property string $nu_documento
 * @property string $dt_vencimento
 * @property string $ds_valor
 * @property string $cod_tipo_contrato_fk
 * @property string $nu_doc
 * @property string $dt_inclusao
 * @property string $nosso_numero
 * @property string $valor_multa
 * @property string $multa
 * @property string $valor_juros
 * @property string $dt_pagamento
 * @property string $fic_comp
 * @property string $fic_comp2
 * @property boolean $advogado
 * @property string $valor_pago
 * @property boolean $ativo
 * @property string $dt_processamento
 * @property string $dt_ocorrencia
 * @property string $dt_exclusao
 * @property string $justificativa_exclusao
 *
 * @property TabTipoContrato $tabTipoContrato
 */
class TabBoleto extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'financeiro.tab_boleto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nu_documento', 'dt_vencimento', 'ds_valor', 'dt_inclusao', 'nosso_numero'], 'required'],
            [['dt_vencimento', 'dt_inclusao', 'multa', 'dt_pagamento', 'dt_processamento', 'dt_ocorrencia', 'dt_exclusao'], 'safe'],
            [['ds_valor', 'valor_multa', 'valor_pago'], 'number'],
            [['cod_tipo_contrato_fk'], 'integer'],
            [['advogado', 'ativo'], 'boolean'],
            [['justificativa_exclusao'], 'string'],
            [['nu_documento', 'nu_doc', 'nosso_numero'], 'string', 'max' => 100],
            [['valor_juros', 'fic_comp', 'fic_comp2'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_boleto' => 'Cod Boleto',
            'nu_documento' => 'Nu Documento',
            'dt_vencimento' => 'Dt Vencimento',
            'ds_valor' => 'Ds Valor',
            'cod_tipo_contrato_fk' => 'Cod Tipo Contrato Fk',
            'nu_doc' => 'Nu Doc',
            'dt_inclusao' => 'Dt Inclusao',
            'nosso_numero' => 'Nosso Numero',
            'valor_multa' => 'Valor Multa',
            'multa' => 'Multa',
            'valor_juros' => 'Valor Juros',
            'dt_pagamento' => 'Dt Pagamento',
            'fic_comp' => 'Fic Comp',
            'fic_comp2' => 'Fic Comp2',
            'advogado' => 'Advogado',
            'valor_pago' => 'Valor Pago',
            'ativo' => 'Ativo',
            'dt_processamento' => 'Dt Processamento',
            'dt_ocorrencia' => 'Dt Ocorrencia',
            'dt_exclusao' => 'Dt Exclusao',
            'justificativa_exclusao' => 'Justificativa Exclusao',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabTipoContrato()
    {
        return $this->hasOne(TabTipoContrato::className(), ['cod_tipo_contrato' => 'cod_tipo_contrato_fk']);
    }

    /**
     * @inheritdoc
     * @return TabBoletoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabBoletoQuery(get_called_class());
    }
}
