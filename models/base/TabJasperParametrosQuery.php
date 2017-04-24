<?php

namespace app\models\base;

/**
 * This is the ActiveQuery class for [[TabJasperParametros]].
 *
 * @see TabJasperParametros
 */
class TabJasperParametrosQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabJasperParametros[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabJasperParametros|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}