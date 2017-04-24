<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "public.tab_municipios".
 *
 * @property string $cod_municipio
 * @property string $txt_nome
 * @property string $sgl_estado_fk
 * @property boolean $bln_indicador_capital
 * @property integer $cod_microrregiao_fk
 * @property integer $cod_ibge
 * @property integer $cod_regiao_metropolitana_fk
 * @property string $area_km2
 * @property string $latitude
 * @property string $longitude
 * @property integer $regiao_hidrografica_fk
 *
 * @property TabMunicipiosAtendidos[] $tabMunicipiosAtendidos
 * @property TabAtributosValores $tabAtributosValores
 * @property TabEstados $tabEstados
 * @property TabMicrorregioes $tabMicrorregioes
 * @property TabRegioesMetropolitanas $tabRegioesMetropolitanas
 * @property TabMunicipiosPopulacoes[] $tabMunicipiosPopulacoes
 * @property TabPrestadores[] $tabPrestadores
 */
class TabMunicipios extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'public.tab_municipios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod_municipio'], 'required'],
            [['bln_indicador_capital'], 'boolean'],
            [['cod_microrregiao_fk', 'cod_ibge', 'cod_regiao_metropolitana_fk', 'regiao_hidrografica_fk'], 'integer'],
            [['area_km2'], 'number'],
            [['cod_municipio'], 'string', 'max' => 6],
            [['txt_nome'], 'string', 'max' => 50],
            [['sgl_estado_fk'], 'string', 'max' => 2],
            [['latitude', 'longitude'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_municipio' => 'Código do munípio',
            'txt_nome' => 'Nome do município',
            'sgl_estado_fk' => 'Sigla município',
            'bln_indicador_capital' => 'Se capital',
            'cod_microrregiao_fk' => 'Microregião',
            'cod_ibge' => 'Código do IBGE',
            'cod_regiao_metropolitana_fk' => 'Região Metropolitana',
            'area_km2' => 'Area Km2',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'regiao_hidrografica_fk' => 'Regiao Hidrografica Fk',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabMunicipiosAtendidos()
    {
        return $this->hasMany(TabMunicipiosAtendidos::className(), ['cod_municipio_fk' => 'cod_municipio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabAtributosValores()
    {
        return $this->hasOne(TabAtributosValores::className(), ['cod_atributos_valores' => 'regiao_hidrografica_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabEstados()
    {
        return $this->hasOne(TabEstados::className(), ['sgl_estado' => 'sgl_estado_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabMicrorregioes()
    {
        return $this->hasOne(TabMicrorregioes::className(), ['cod_microrregiao' => 'cod_microrregiao_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabRegioesMetropolitanas()
    {
        return $this->hasOne(TabRegioesMetropolitanas::className(), ['cod_regiao_metropolitana' => 'cod_regiao_metropolitana_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabMunicipiosPopulacoes()
    {
        return $this->hasMany(TabMunicipiosPopulacoes::className(), ['municipio_fk' => 'cod_municipio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabPrestadores()
    {
        return $this->hasMany(TabPrestadores::className(), ['cod_municipio_fk' => 'cod_municipio']);
    }

    /**
     * @inheritdoc
     * @return TabMunicipiosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabMunicipiosQuery(get_called_class());
    }
}
