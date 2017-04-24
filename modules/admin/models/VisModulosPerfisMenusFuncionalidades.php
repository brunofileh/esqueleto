<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "acesso.vis_modulos_perfis_menus_funcionalidades".
 *
 * @property integer $cod_modulo
 * @property string $nome_modulo
 * @property string $dsc_modulo
 * @property integer $cod_perfil
 * @property string $nome_perfil
 * @property string $dsc_perfil
 * @property string $txt_perfil_prestador
 * @property integer $cod_menu_pai
 * @property string $nome_menu_pai
 * @property string $dsc_menu_pai
 * @property integer $cod_menu
 * @property string $nome_menu
 * @property string $dsc_menu
 * @property integer $num_ordem
 * @property integer $cod_funcionalidade
 * @property string $nome_funcionalidade
 * @property string $dsc_funcionalidade
 */
class VisModulosPerfisMenusFuncionalidades extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acesso.vis_modulos_perfis_menus_funcionalidades';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod_modulo', 'cod_perfil', 'cod_menu_pai', 'cod_menu', 'num_ordem', 'cod_funcionalidade'], 'integer'],
            [['nome_modulo', 'nome_perfil', 'nome_menu_pai', 'nome_menu', 'nome_funcionalidade'], 'string', 'max' => 80],
            [['dsc_modulo', 'dsc_perfil', 'dsc_menu_pai', 'dsc_menu', 'dsc_funcionalidade'], 'string', 'max' => 150],
            [['txt_perfil_prestador'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_modulo' => 'Cod Modulo',
            'nome_modulo' => 'Nome Modulo',
            'dsc_modulo' => 'Dsc Modulo',
            'cod_perfil' => 'Cod Perfil',
            'nome_perfil' => 'Nome Perfil',
            'dsc_perfil' => 'Dsc Perfil',
            'txt_perfil_prestador' => 'Txt Perfil Prestador',
            'cod_menu_pai' => 'Cod Menu Pai',
            'nome_menu_pai' => 'Nome Menu Pai',
            'dsc_menu_pai' => 'Dsc Menu Pai',
            'cod_menu' => 'Cod Menu',
            'nome_menu' => 'Nome Menu',
            'dsc_menu' => 'Dsc Menu',
            'num_ordem' => 'Num Ordem',
            'cod_funcionalidade' => 'Cod Funcionalidade',
            'nome_funcionalidade' => 'Nome Funcionalidade',
            'dsc_funcionalidade' => 'Dsc Funcionalidade',
        ];
    }

    /**
     * @inheritdoc
     * @return VisModulosPerfisMenusFuncionalidadesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VisModulosPerfisMenusFuncionalidadesQuery(get_called_class());
    }
}
