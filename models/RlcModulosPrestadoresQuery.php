<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[RlcModulosPrestadores]].
 *
 * @see RlcModulosPrestadores
 */
class RlcModulosPrestadoresQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return RlcModulosPrestadores[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return RlcModulosPrestadores|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}