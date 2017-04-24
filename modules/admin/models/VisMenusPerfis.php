<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "acesso.vis_menus_perfis".
 *
 * @property string $nome_menu
 * @property string $dsc_menu
 * @property string $txt_url
 * @property string $txt_imagem
 * @property integer $num_ordem
 * @property integer $num_nivel
 * @property string $nome_perfil
 * @property string $dsc_perfil
 * @property string $nome_menu_pai
 * @property string $dsc_menu_pai
 * @property integer $cod_perfil_fk
 * @property integer $cod_perfil_funcionalidade_acao_fk
 * @property integer $cod_menu_fk
 * @property integer $cod_menu_pai_fk
 * @property integer $cod_modulo_fk
 * @property integer $cod_usuario_fk
 */
class VisMenusPerfis extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acesso.vis_menus_perfis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['num_ordem', 'num_nivel', 'cod_perfil_fk', 'cod_perfil_funcionalidade_acao_fk', 'cod_menu_fk', 'cod_menu_pai_fk', 'cod_modulo_fk', 'cod_usuario_fk'], 'integer'],
            [['nome_menu', 'nome_perfil', 'nome_menu_pai'], 'string', 'max' => 80],
            [['dsc_menu', 'dsc_perfil', 'dsc_menu_pai'], 'string', 'max' => 150],
            [['txt_url', 'txt_imagem'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nome_menu' => 'Nome Menu',
            'dsc_menu' => 'Dsc Menu',
            'txt_url' => 'Txt Url',
            'txt_imagem' => 'Txt Imagem',
            'num_ordem' => 'Num Ordem',
            'num_nivel' => 'Num Nivel',
            'nome_perfil' => 'Nome Perfil',
            'dsc_perfil' => 'Dsc Perfil',
            'nome_menu_pai' => 'Nome Menu Pai',
            'dsc_menu_pai' => 'Dsc Menu Pai',
            'cod_perfil_fk' => 'Cod Perfil Fk',
            'cod_perfil_funcionalidade_acao_fk' => 'Cod Perfil Funcionalidade Acao Fk',
            'cod_menu_fk' => 'Cod Menu Fk',
            'cod_menu_pai_fk' => 'Cod Menu Pai Fk',
            'cod_modulo_fk' => 'Cod Modulo Fk',
            'cod_usuario_fk' => 'Cod Usuario Fk',
        ];
    }

    /**
     * @inheritdoc
     * @return VisMenusPerfisQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VisMenusPerfisQuery(get_called_class());
    }
}
