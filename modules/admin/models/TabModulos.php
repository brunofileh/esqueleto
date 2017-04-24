<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "acesso.tab_modulos".
 *
 * @property string $cod_modulo
 * @property string $txt_nome
 * @property string $id
 * @property string $dsc_modulo
 * @property string $txt_url
 * @property string $txt_icone
 * @property string $txt_login_inclusao
 * @property string $dte_inclusao
 * @property string $dte_alteracao
 * @property string $dte_exclusao
 * @property string $txt_tema
 * @property boolean $bln_coleta
 * @property string $txt_equipe
 * @property string $txt_email_equipe
 * @property string $num_fones_equipe
 * @property string $flag_inicio_equipe
 *
 * @property TabPerfis[] $tabPerfis
 * @property TabUsuariosOpcoes[] $tabUsuariosOpcoes
 * @property TabAvisosErros[] $tabAvisosErros
 * @property TabBlocoInfo[] $tabBlocoInfo
 * @property TabForm[] $tabForm
 * @property TabGlossarios[] $tabGlossarios
 * @property RlcModulosPrestadores[] $rlcModulosPrestadores
 * @property TabContatos[] $tabContatos
 * @property TabMalaDiretaLog[] $tabMalaDiretaLog
 * @property TabModeloDocs[] $tabModeloDocs
 * @property TabParametros[] $tabParametros
 */
class TabModulos extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acesso.tab_modulos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['txt_nome', 'txt_equipe', 'txt_email_equipe', 'num_fones_equipe', 'flag_inicio_equipe'], 'required'],
            [['dte_inclusao', 'dte_alteracao', 'dte_exclusao'], 'safe'],
            [['bln_coleta'], 'boolean'],
            [['txt_nome', 'txt_email_equipe'], 'string', 'max' => 80],
            [['id'], 'string', 'max' => 40],
            [['dsc_modulo', 'txt_login_inclusao'], 'string', 'max' => 150],
            [['txt_url'], 'string', 'max' => 200],
            [['txt_icone'], 'string', 'max' => 100],
            [['txt_tema'], 'string', 'max' => 20],
            [['txt_equipe', 'num_fones_equipe'], 'string', 'max' => 50],
            [['flag_inicio_equipe'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_modulo' => 'Cod Modulo',
            'txt_nome' => 'Nome',
            'id' => 'ID',
            'dsc_modulo' => 'Descrição',
            'txt_url' => 'URL',
            'txt_icone' => 'Ícone',
            'txt_login_inclusao' => 'Usuário da Inclusão',
            'dte_inclusao' => 'Data da Inclusão',
            'dte_alteracao' => 'Data da Alteração',
            'dte_exclusao' => 'Data da Exclusão',
            'txt_tema' => 'Txt Tema',
            'bln_coleta' => 'Se o modulo é de coleta',
            'txt_equipe' => 'Txt Equipe',
            'txt_email_equipe' => 'Txt Email Equipe',
            'num_fones_equipe' => 'Num Fones Equipe',
            'flag_inicio_equipe' => 'Flag Inicio Equipe',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabPerfis()
    {
        return $this->hasMany(TabPerfis::className(), ['cod_modulo_fk' => 'cod_modulo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabUsuariosOpcoes()
    {
        return $this->hasMany(TabUsuariosOpcoes::className(), ['fk_modulo' => 'cod_modulo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabAvisosErros()
    {
        return $this->hasMany(TabAvisosErros::className(), ['fk_modulo' => 'cod_modulo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabBlocoInfo()
    {
        return $this->hasMany(TabBlocoInfo::className(), ['servico_fk' => 'cod_modulo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabForm()
    {
        return $this->hasMany(TabForm::className(), ['cod_tipo_servico' => 'cod_modulo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabGlossarios()
    {
        return $this->hasMany(TabGlossarios::className(), ['fk_attr_servico' => 'cod_modulo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRlcModulosPrestadores()
    {
        return $this->hasMany(RlcModulosPrestadores::className(), ['cod_modulo_fk' => 'cod_modulo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabContatos()
    {
        return $this->hasMany(TabContatos::className(), ['cod_modulo_fk' => 'cod_modulo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabMalaDiretaLog()
    {
        return $this->hasMany(TabMalaDiretaLog::className(), ['modulo_fk' => 'cod_modulo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabModeloDocs()
    {
        return $this->hasMany(TabModeloDocs::className(), ['modulo_fk' => 'cod_modulo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabParametros()
    {
        return $this->hasMany(TabParametros::className(), ['modulo_fk' => 'cod_modulo']);
    }

    /**
     * @inheritdoc
     * @return TabModulosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabModulosQuery(get_called_class());
    }
}
