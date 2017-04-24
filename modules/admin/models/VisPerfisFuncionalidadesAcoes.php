<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "acesso.vis_perfis_funcionalidades_acoes".
 *
 * @property integer $cod_perfil_funcionalidade_acao
 * @property integer $cod_perfil
 * @property string $dsc_funcionalidade
 * @property string $dsc_acao
 * @property string $funcionalidade_acao
 */
class VisPerfisFuncionalidadesAcoes extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acesso.vis_perfis_funcionalidades_acoes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod_perfil_funcionalidade_acao', 'cod_perfil'], 'integer'],
            [['funcionalidade_acao'], 'string'],
            [['dsc_funcionalidade', 'dsc_acao'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_perfil_funcionalidade_acao' => 'Cod Perfil Funcionalidade Acao',
            'cod_perfil' => 'Cod Perfil',
            'dsc_funcionalidade' => 'Dsc Funcionalidade',
            'dsc_acao' => 'Dsc Acao',
            'funcionalidade_acao' => 'Funcionalidade Acao',
        ];
    }

    /**
     * @inheritdoc
     * @return VisPerfisFuncionalidadesAcoesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VisPerfisFuncionalidadesAcoesQuery(get_called_class());
    }
}
