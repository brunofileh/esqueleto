<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TabContatos]].
 *
 * @see TabContatos
 */
class TabContatosQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabContatos[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabContatos|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}