<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[TabAcoes]].
 *
 * @see TabAcoes
 */
class TabAcoesQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabAcoes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabAcoes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}