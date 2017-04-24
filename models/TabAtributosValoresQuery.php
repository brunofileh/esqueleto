<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TabAtributosValores]].
 *
 * @see TabAtributosValores
 */
class TabAtributosValoresQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabAtributosValores[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabAtributosValores|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}