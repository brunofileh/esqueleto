<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TabMesorregioes]].
 *
 * @see TabMesorregioes
 */
class TabMesorregioesQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabMesorregioes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabMesorregioes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}