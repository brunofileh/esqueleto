<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TabMicrorregioes]].
 *
 * @see TabMicrorregioes
 */
class TabMicrorregioesQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabMicrorregioes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabMicrorregioes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}