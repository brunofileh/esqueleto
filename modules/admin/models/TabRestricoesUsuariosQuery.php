<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[TabRestricoesUsuarios]].
 *
 * @see TabRestricoesUsuarios
 */
class TabRestricoesUsuariosQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabRestricoesUsuarios[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabRestricoesUsuarios|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}