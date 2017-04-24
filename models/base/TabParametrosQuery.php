<?php

namespace app\models\base;

/**
 * This is the ActiveQuery class for [[TabParametros]].
 *
 * @see TabParametros
 */
class TabParametrosQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabParametros[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabParametros|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}