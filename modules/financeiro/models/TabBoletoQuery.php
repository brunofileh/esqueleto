<?php

namespace app\modules\financeiro\models;

/**
 * This is the ActiveQuery class for [[TabBoleto]].
 *
 * @see TabBoleto
 */
class TabBoletoQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabBoleto[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabBoleto|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}