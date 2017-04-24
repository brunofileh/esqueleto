<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[VisModulosPerfisMenusFuncionalidades]].
 *
 * @see VisModulosPerfisMenusFuncionalidades
 */
class VisModulosPerfisMenusFuncionalidadesQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return VisModulosPerfisMenusFuncionalidades[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VisModulosPerfisMenusFuncionalidades|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}