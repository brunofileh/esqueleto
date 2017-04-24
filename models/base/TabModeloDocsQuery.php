<?php

namespace app\models\base;

/**
 * This is the ActiveQuery class for [[TabModeloDocs]].
 *
 * @see TabModeloDocs
 */
class TabModeloDocsQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabModeloDocs[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabModeloDocs|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}