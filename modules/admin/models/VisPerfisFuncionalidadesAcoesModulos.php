<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "acesso.vis_perfis_funcionalidades_acoes_modulos".
 *
 * @property string $nome_perfil
 * @property string $dsc_perfil
 * @property string $nome_funcionalidade
 * @property string $dsc_funcionalidade
 * @property string $nome_acao
 * @property string $dsc_acao
 * @property string $txt_url
 * @property string $txt_icone
 * @property string $nome_modulo
 * @property string $modulo_id
 * @property string $dsc_modulo
 * @property string $cod_perfil_fk
 * @property string $cod_funcionalidade_fk
 * @property string $cod_acao_fk
 * @property string $cod_modulo_fk
 * @property string $cod_usuario_fk
 * @property string $cod_perfil_funcionalidade_acao_fk
 */
class VisPerfisFuncionalidadesAcoesModulos extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acesso.vis_perfis_funcionalidades_acoes_modulos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod_perfil_fk', 'cod_funcionalidade_fk', 'cod_acao_fk', 'cod_modulo_fk', 'cod_usuario_fk', 'cod_perfil_funcionalidade_acao_fk'], 'integer'],
            [['nome_perfil', 'nome_funcionalidade', 'nome_modulo'], 'string', 'max' => 80],
            [['dsc_perfil', 'dsc_funcionalidade', 'dsc_acao', 'dsc_modulo'], 'string', 'max' => 150],
            [['nome_acao'], 'string', 'max' => 45],
            [['txt_url'], 'string', 'max' => 200],
            [['txt_icone'], 'string', 'max' => 100],
            [['modulo_id'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nome_perfil' => 'Nome Perfil',
            'dsc_perfil' => 'Dsc Perfil',
            'nome_funcionalidade' => 'Nome Funcionalidade',
            'dsc_funcionalidade' => 'Dsc Funcionalidade',
            'nome_acao' => 'Nome Acao',
            'dsc_acao' => 'Dsc Acao',
            'txt_url' => 'Txt Url',
            'txt_icone' => 'Txt Icone',
            'nome_modulo' => 'Nome Modulo',
            'modulo_id' => 'Modulo ID',
            'dsc_modulo' => 'Dsc Modulo',
            'cod_perfil_fk' => 'Cod Perfil Fk',
            'cod_funcionalidade_fk' => 'Cod Funcionalidade Fk',
            'cod_acao_fk' => 'Cod Acao Fk',
            'cod_modulo_fk' => 'Cod Modulo Fk',
            'cod_usuario_fk' => 'Cod Usuario Fk',
            'cod_perfil_funcionalidade_acao_fk' => 'Cod Perfil Funcionalidade Acao Fk',
        ];
    }

    /**
     * @inheritdoc
     * @return VisPerfisFuncionalidadesAcoesModulosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VisPerfisFuncionalidadesAcoesModulosQuery(get_called_class());
    }
}
