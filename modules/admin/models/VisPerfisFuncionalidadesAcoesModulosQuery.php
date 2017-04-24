<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[VisPerfisFuncionalidadesAcoesModulos]].
 *
 * @see VisPerfisFuncionalidadesAcoesModulos
 */
class VisPerfisFuncionalidadesAcoesModulosQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return VisPerfisFuncionalidadesAcoesModulos[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VisPerfisFuncionalidadesAcoesModulos|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}