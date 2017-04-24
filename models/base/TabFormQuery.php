<?php

namespace app\models\base;

/**
 * This is the ActiveQuery class for [[TabForm]].
 *
 * @see TabForm
 */
class TabFormQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabForm[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabForm|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}