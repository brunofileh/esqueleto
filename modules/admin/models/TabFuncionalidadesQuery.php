<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[TabFuncionalidades]].
 *
 * @see TabFuncionalidades
 */
class TabFuncionalidadesQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabFuncionalidades[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabFuncionalidades|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}