<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TabAtributos]].
 *
 * @see TabAtributos
 */
class TabAtributosQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabAtributos[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabAtributos|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}