<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[VisMenusPerfis]].
 *
 * @see VisMenusPerfis
 */
class VisMenusPerfisQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return VisMenusPerfis[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VisMenusPerfis|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}