<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TabEstados]].
 *
 * @see TabEstados
 */
class TabEstadosQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabEstados[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabEstados|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}