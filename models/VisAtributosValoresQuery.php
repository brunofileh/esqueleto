<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[VisAtributosValores]].
 *
 * @see VisAtributosValores
 */
class VisAtributosValoresQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return VisAtributosValores[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VisAtributosValores|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}