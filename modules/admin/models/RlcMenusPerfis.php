<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "acesso.rlc_menus_perfis".
 *
 * @property integer $cod_menu_perfil
 * @property integer $cod_perfil_fk
 * @property integer $cod_menu_fk
 * @property string $txt_login_inclusao
 * @property string $dte_inclusao
 * @property string $dte_alteracao
 * @property string $dte_exclusao
 *
 * @property TabMenus $tabMenus
 * @property TabPerfis $tabPerfis
 */
class RlcMenusPerfis extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acesso.rlc_menus_perfis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod_perfil_fk', 'cod_menu_fk'], 'required'],
            [['cod_perfil_fk', 'cod_menu_fk'], 'integer'],
            [['dte_inclusao', 'dte_alteracao', 'dte_exclusao'], 'safe'],
            [['txt_login_inclusao'], 'string', 'max' => 150],
            [['cod_perfil_fk', 'cod_menu_fk'], 'unique', 'targetAttribute' => ['cod_perfil_fk', 'cod_menu_fk'], 'message' => 'The combination of Cod Perfil Fk and Cod Menu Fk has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_menu_perfil' => 'Cod Menu Perfil',
            'cod_perfil_fk' => 'Cod Perfil Fk',
            'cod_menu_fk' => 'Cod Menu Fk',
            'txt_login_inclusao' => 'Usuário da Inclusão',
            'dte_inclusao' => 'Data da Inclusão',
            'dte_alteracao' => 'Data da Alteração',
            'dte_exclusao' => 'Data da Exclusão',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabMenus()
    {
        return $this->hasOne(TabMenus::className(), ['cod_menu' => 'cod_menu_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabPerfis()
    {
        return $this->hasOne(TabPerfis::className(), ['cod_perfil' => 'cod_perfil_fk']);
    }

    /**
     * @inheritdoc
     * @return RlcMenusPerfisQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RlcMenusPerfisQuery(get_called_class());
    }
}
