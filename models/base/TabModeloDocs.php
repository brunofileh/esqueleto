<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "tab_modelo_docs".
 *
 * @property integer $cod_modelo_doc
 * @property string $sgl_id
 * @property string $modulo_fk
 * @property integer $cabecalho_fk
 * @property integer $rodape_fk
 * @property integer $tipo_modelo_documento_fk
 * @property integer $finalidade_fk
 * @property string $txt_descricao
 * @property string $txt_conteudo
 * @property string $dte_inclusao
 * @property string $dte_alteracao
 * @property string $dte_exclusao
 * @property string $txt_login_inclusao
 *
 * @property TabModulos $tabModulos
 * @property TabAtributosValores $tabAtributosValores
 * @property TabAtributosValores $tabAtributosValores0
 * @property TabAtributosValores $tabAtributosValores1
 * @property TabAtributosValores $tabAtributosValores2
 */
class TabModeloDocs extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tab_modelo_docs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sgl_id', 'cabecalho_fk', 'rodape_fk', 'tipo_modelo_documento_fk', 'finalidade_fk', 'txt_conteudo'], 'required'],
            [['modulo_fk', 'cabecalho_fk', 'rodape_fk', 'tipo_modelo_documento_fk', 'finalidade_fk'], 'integer'],
            [['txt_conteudo'], 'string'],
            [['dte_inclusao', 'dte_alteracao', 'dte_exclusao'], 'safe'],
            [['sgl_id'], 'string', 'max' => 30],
            [['txt_descricao'], 'string', 'max' => 100],
            [['txt_login_inclusao'], 'string', 'max' => 150],
            [['modulo_fk', 'sgl_id'], 'unique', 'targetAttribute' => ['modulo_fk', 'sgl_id'], 'message' => 'The combination of Sgl ID and Modulo Fk has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_modelo_doc' => 'Cod Modelo Doc',
            'sgl_id' => 'Sgl ID',
            'modulo_fk' => 'Modulo Fk',
            'cabecalho_fk' => 'Cabecalho Fk',
            'rodape_fk' => 'Rodape Fk',
            'tipo_modelo_documento_fk' => 'Tipo Modelo Documento Fk',
            'finalidade_fk' => 'Finalidade Fk',
            'txt_descricao' => 'Txt Descricao',
            'txt_conteudo' => 'Txt Conteudo',
            'dte_inclusao' => 'Dte Inclusao',
            'dte_alteracao' => 'Dte Alteracao',
            'dte_exclusao' => 'Dte Exclusao',
            'txt_login_inclusao' => 'Txt Login Inclusao',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabModulos()
    {
        return $this->hasOne(TabModulos::className(), ['cod_modulo' => 'modulo_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabAtributosValores()
    {
        return $this->hasOne(TabAtributosValores::className(), ['cod_atributos_valores' => 'cabecalho_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabAtributosValores0()
    {
        return $this->hasOne(TabAtributosValores::className(), ['cod_atributos_valores' => 'rodape_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabAtributosValores1()
    {
        return $this->hasOne(TabAtributosValores::className(), ['cod_atributos_valores' => 'tipo_modelo_documento_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabAtributosValores2()
    {
        return $this->hasOne(TabAtributosValores::className(), ['cod_atributos_valores' => 'finalidade_fk']);
    }

    /**
     * @inheritdoc
     * @return TabModeloDocsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabModeloDocsQuery(get_called_class());
    }
}
