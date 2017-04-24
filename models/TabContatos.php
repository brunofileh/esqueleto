<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tab_contatos".
 *
 * @property integer $cod_contato
 * @property integer $cod_contato_relativo_fk
 * @property integer $cod_modulo_fk
 * @property string $num_telefone
 * @property string $txt_email
 * @property string $dte_contato
 * @property integer $cod_form_fk
 * @property integer $cod_categoria_fk
 * @property integer $cod_sub_categoria_fk
 * @property integer $cod_forma_contato_fk
 * @property integer $cod_tipo_contato_fk
 * @property string $dte_atendimento
 * @property integer $cod_situacao_fk
 * @property integer $cod_usuario_responsavel_fk
 * @property integer $cod_resposta_padrao_fk
 * @property integer $cod_usuario_solicitante_fk
 * @property string $txt_login_inclusao
 * @property string $dte_inclusao
 * @property string $dte_alteracao
 * @property string $dte_exclusao
 * @property string $txt_descricao
 * @property integer $cod_prestador_fk
 * @property string $txt_nome
 * @property string $num_ano_ref
 * @property string $txt_resposta
 *
 * @property TabModulos $tabModulos
 * @property TabUsuarios $tabUsuarios
 * @property TabUsuarios $tabUsuarios0
 * @property TabAtributosValores $tabAtributosValores
 * @property TabAtributosValores $tabAtributosValores0
 * @property TabAtributosValores $tabAtributosValores1
 * @property TabAtributosValores $tabAtributosValores2
 * @property TabAtributosValores $tabAtributosValores3
 * @property TabAtributosValores $tabAtributosValores4
 * @property TabAtributosValores $tabAtributosValores5
 * @property TabContatos $tabContatos
 * @property TabContatos[] $tabContatos0
 * @property TabPrestadores $tabPrestadores
 */
class TabContatos extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tab_contatos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod_contato_relativo_fk', 'cod_modulo_fk', 'cod_form_fk', 'cod_categoria_fk', 'cod_sub_categoria_fk', 'cod_forma_contato_fk', 'cod_tipo_contato_fk', 'cod_situacao_fk', 'cod_usuario_responsavel_fk', 'cod_resposta_padrao_fk', 'cod_usuario_solicitante_fk', 'cod_prestador_fk', 'num_ano_ref'], 'integer'],
            [['dte_contato', 'dte_atendimento', 'dte_inclusao', 'dte_alteracao', 'dte_exclusao'], 'safe'],
            [['txt_descricao', 'txt_resposta'], 'string'],
            [['num_telefone'], 'string', 'max' => 15],
            [['txt_email'], 'string', 'max' => 100],
            [['txt_login_inclusao'], 'string', 'max' => 150],
            [['txt_nome'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_contato' => 'Cod Contato',
            'cod_contato_relativo_fk' => 'Cod Contato Relativo Fk',
            'cod_modulo_fk' => 'Cod Modulo Fk',
            'num_telefone' => 'Telefone',
            'txt_email' => 'E-mail',
            'dte_contato' => 'Data do contato',
            'cod_form_fk' => 'Formulário',
            'cod_categoria_fk' => 'Categoria',
            'cod_sub_categoria_fk' => 'Subcategoria',
            'cod_forma_contato_fk' => 'Forma de contato',
            'cod_tipo_contato_fk' => 'Tipo de contato',
            'dte_atendimento' => 'Data do atendimento',
            'cod_situacao_fk' => 'Situação',
            'cod_usuario_responsavel_fk' => 'Analista / Atendente',
            'cod_resposta_padrao_fk' => 'Resposta padrão',
            'cod_usuario_solicitante_fk' => 'Cod Usuario Solicitante Fk',
            'txt_login_inclusao' => 'Txt Login Inclusao',
            'dte_inclusao' => 'Dte Inclusao',
            'dte_alteracao' => 'Dte Alteracao',
            'dte_exclusao' => 'Dte Exclusao',
            'txt_descricao' => 'Descrição',
            'cod_prestador_fk' => 'Cod Prestador Fk',
            'txt_nome' => 'Nome do contato',
            'num_ano_ref' => 'Num Ano Ref',
            'txt_resposta' => 'Resposta',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabModulos()
    {
        return $this->hasOne(TabModulos::className(), ['cod_modulo' => 'cod_modulo_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabUsuarios()
    {
        return $this->hasOne(TabUsuarios::className(), ['cod_usuario' => 'cod_usuario_responsavel_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabUsuarios0()
    {
        return $this->hasOne(TabUsuarios::className(), ['cod_usuario' => 'cod_usuario_solicitante_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabAtributosValores()
    {
        return $this->hasOne(TabAtributosValores::className(), ['cod_atributos_valores' => 'cod_form_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabAtributosValores0()
    {
        return $this->hasOne(TabAtributosValores::className(), ['cod_atributos_valores' => 'cod_categoria_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabAtributosValores1()
    {
        return $this->hasOne(TabAtributosValores::className(), ['cod_atributos_valores' => 'cod_sub_categoria_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabAtributosValores2()
    {
        return $this->hasOne(TabAtributosValores::className(), ['cod_atributos_valores' => 'cod_forma_contato_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabAtributosValores3()
    {
        return $this->hasOne(TabAtributosValores::className(), ['cod_atributos_valores' => 'cod_tipo_contato_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabAtributosValores4()
    {
        return $this->hasOne(TabAtributosValores::className(), ['cod_atributos_valores' => 'cod_situacao_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabAtributosValores5()
    {
        return $this->hasOne(TabAtributosValores::className(), ['cod_atributos_valores' => 'cod_resposta_padrao_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabContatos()
    {
        return $this->hasOne(TabContatos::className(), ['cod_contato' => 'cod_contato_relativo_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabContatos0()
    {
        return $this->hasMany(TabContatos::className(), ['cod_contato_relativo_fk' => 'cod_contato']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabPrestadores()
    {
        return $this->hasOne(TabPrestadores::className(), ['cod_prestador' => 'cod_prestador_fk']);
    }

    /**
     * @inheritdoc
     * @return TabContatosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabContatosQuery(get_called_class());
    }
}
