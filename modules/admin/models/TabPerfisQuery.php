<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[TabPerfis]].
 *
 * @see TabPerfis
 */
class TabPerfisQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabPerfis[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabPerfis|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}