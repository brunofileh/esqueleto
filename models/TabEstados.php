<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "public.tab_estados".
 *
 * @property string $sgl_estado
 * @property string $cod_estado
 * @property string $txt_nome
 * @property string $cod_cpt_est
 * @property integer $qtd_mun_est
 * @property string $vlr_taxa_hab_dom
 * @property integer $cod_regiao_geografica
 */
class TabEstados extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'public.tab_estados';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sgl_estado', 'cod_estado', 'txt_nome', 'cod_cpt_est'], 'required'],
            [['qtd_mun_est', 'cod_regiao_geografica'], 'integer'],
            [['vlr_taxa_hab_dom'], 'number'],
            [['sgl_estado', 'cod_estado'], 'string', 'max' => 2],
            [['txt_nome'], 'string', 'max' => 20],
            [['cod_cpt_est'], 'string', 'max' => 6]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sgl_estado' => 'SIgla do Estado',
            'cod_estado' => 'COD_EST',
            'txt_nome' => 'Nome do estado',
            'cod_cpt_est' => 'COD_CPT_EST',
            'qtd_mun_est' => 'QTD_MUN_EST',
            'vlr_taxa_hab_dom' => 'TX_HAB_DOM',
            'cod_regiao_geografica' => 'Código da Região Geográfica',
        ];
    }

    /**
     * @inheritdoc
     * @return TabEstadosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabEstadosQuery(get_called_class());
    }
}
