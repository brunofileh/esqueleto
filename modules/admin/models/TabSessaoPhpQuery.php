<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[TabSessaoPhp]].
 *
 * @see TabSessaoPhp
 */
class TabSessaoPhpQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabSessaoPhp[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabSessaoPhp|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}