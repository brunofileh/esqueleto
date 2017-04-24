<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[TabModulos]].
 *
 * @see TabModulos
 */
class TabModulosQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabModulos[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabModulos|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}