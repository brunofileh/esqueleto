<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[TabUsuarios]].
 *
 * @see TabUsuarios
 */
class TabUsuariosQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabUsuarios[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabUsuarios|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}