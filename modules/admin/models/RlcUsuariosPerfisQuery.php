<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[RlcUsuariosPerfis]].
 *
 * @see RlcUsuariosPerfis
 */
class RlcUsuariosPerfisQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return RlcUsuariosPerfis[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return RlcUsuariosPerfis|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}