<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[VisUsuariosPerfis]].
 *
 * @see VisUsuariosPerfis
 */
class VisUsuariosPerfisQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return VisUsuariosPerfis[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VisUsuariosPerfis|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}