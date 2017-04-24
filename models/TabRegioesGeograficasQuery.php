<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TabRegioesGeograficas]].
 *
 * @see TabRegioesGeograficas
 */
class TabRegioesGeograficasQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabRegioesGeograficas[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabRegioesGeograficas|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}