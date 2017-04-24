<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[RlcPerfisFuncionalidadesAcoes]].
 *
 * @see RlcPerfisFuncionalidadesAcoes
 */
class RlcPerfisFuncionalidadesAcoesQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return RlcPerfisFuncionalidadesAcoes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return RlcPerfisFuncionalidadesAcoes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}