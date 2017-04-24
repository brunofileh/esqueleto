<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "acesso.tab_menus".
 *
 * @property integer $cod_menu
 * @property string $txt_nome
 * @property string $dsc_menu
 * @property string $txt_url
 * @property string $txt_imagem
 * @property integer $num_ordem
 * @property integer $num_nivel
 * @property integer $cod_perfil_funcionalidade_acao_fk
 * @property integer $cod_menu_fk
 * @property string $txt_login_inclusao
 * @property string $dte_inclusao
 * @property string $dte_alteracao
 * @property string $dte_exclusao
 *
 * @property RlcMenusPerfis[] $rlcMenusPerfis
 * @property TabFuncionalidades $tabFuncionalidades
 * @property TabMenus $tabMenus
 * @property TabMenus[] $tabMenus0
 */
class TabMenus extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acesso.tab_menus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['txt_nome'], 'required'],
            [['num_ordem', 'num_nivel', 'cod_perfil_funcionalidade_acao_fk', 'cod_menu_fk'], 'integer'],
            [['dte_inclusao', 'dte_alteracao', 'dte_exclusao'], 'safe'],
            [['txt_nome'], 'string', 'max' => 80],
            [['dsc_menu', 'txt_login_inclusao'], 'string', 'max' => 150],
            [['txt_url', 'txt_imagem'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_menu' => 'Cod Menu',
            'txt_nome' => 'Nome',
            'dsc_menu' => 'Descrição',
            'txt_url' => 'URL',
            'txt_imagem' => 'Imagem',
            'num_ordem' => 'Ordem',
            'num_nivel' => 'Nível',
            'cod_perfil_funcionalidade_acao_fk' => 'Cod Perfil Funcionalidade Acao Fk',
            'cod_menu_fk' => 'Menu Pai',
            'txt_login_inclusao' => 'Usuário da Inclusão',
            'dte_inclusao' => 'Data da Inclusão',
            'dte_alteracao' => 'Data da Alteração',
            'dte_exclusao' => 'Data da Exclusão',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRlcMenusPerfis()
    {
        return $this->hasMany(RlcMenusPerfis::className(), ['cod_menu_fk' => 'cod_menu']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabFuncionalidades()
    {
        return $this->hasOne(TabFuncionalidades::className(), ['cod_funcionalidade' => 'cod_perfil_funcionalidade_acao_fk']);
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
    public function getTabMenus0()
    {
        return $this->hasMany(TabMenus::className(), ['cod_menu_fk' => 'cod_menu']);
    }

    /**
     * @inheritdoc
     * @return TabMenusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabMenusQuery(get_called_class());
    }
}
