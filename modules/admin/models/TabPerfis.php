<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "acesso.tab_perfis".
 *
 * @property integer $cod_perfil
 * @property string $txt_nome
 * @property string $dsc_perfil
 * @property integer $cod_modulo_fk
 * @property string $txt_login_inclusao
 * @property string $dte_inclusao
 * @property string $dte_alteracao
 * @property string $dte_exclusao
 * @property string $txt_perfil_prestador
 *
 * @property RlcMenusPerfis[] $rlcMenusPerfis
 * @property RlcPerfisFuncionalidadesAcoes[] $rlcPerfisFuncionalidadesAcoes
 * @property RlcUsuariosPerfis[] $rlcUsuariosPerfis
 * @property TabModulos $tabModulos
 */
class TabPerfis extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acesso.tab_perfis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['txt_nome', 'cod_modulo_fk'], 'required'],
            [['cod_modulo_fk'], 'integer'],
            [['dte_inclusao', 'dte_alteracao', 'dte_exclusao'], 'safe'],
            [['txt_nome'], 'string', 'max' => 80],
            [['dsc_perfil', 'txt_login_inclusao'], 'string', 'max' => 150],
            [['txt_perfil_prestador'], 'string', 'max' => 1],
            [['txt_nome', 'cod_modulo_fk'], 'unique', 'targetAttribute' => ['txt_nome', 'cod_modulo_fk'], 'message' => 'The combination of Nome and Cod Modulo Fk has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_perfil' => 'Cod Perfil',
            'txt_nome' => 'Nome',
            'dsc_perfil' => 'Descrição',
            'cod_modulo_fk' => 'Cod Modulo Fk',
            'txt_login_inclusao' => 'Usuário da Inclusão',
            'dte_inclusao' => 'Data da Inclusão',
            'dte_alteracao' => 'Data da Alteração',
            'dte_exclusao' => 'Data da Exclusão',
            'txt_perfil_prestador' => 'Perfil de Prestador',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRlcMenusPerfis()
    {
        return $this->hasMany(RlcMenusPerfis::className(), ['cod_perfil_fk' => 'cod_perfil']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRlcPerfisFuncionalidadesAcoes()
    {
        return $this->hasMany(RlcPerfisFuncionalidadesAcoes::className(), ['cod_perfil_fk' => 'cod_perfil']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRlcUsuariosPerfis()
    {
        return $this->hasMany(RlcUsuariosPerfis::className(), ['cod_perfil_fk' => 'cod_perfil']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabModulos()
    {
        return $this->hasOne(TabModulos::className(), ['cod_modulo' => 'cod_modulo_fk']);
    }

    /**
     * @inheritdoc
     * @return TabPerfisQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabPerfisQuery(get_called_class());
    }
}
