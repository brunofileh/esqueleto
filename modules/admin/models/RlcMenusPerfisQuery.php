<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[RlcMenusPerfis]].
 *
 * @see RlcMenusPerfis
 */
class RlcMenusPerfisQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return RlcMenusPerfis[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return RlcMenusPerfis|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}