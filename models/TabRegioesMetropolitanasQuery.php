<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TabRegioesMetropolitanas]].
 *
 * @see TabRegioesMetropolitanas
 */
class TabRegioesMetropolitanasQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabRegioesMetropolitanas[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabRegioesMetropolitanas|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}