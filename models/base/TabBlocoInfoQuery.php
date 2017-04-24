<?php

namespace app\models\base;

/**
 * This is the ActiveQuery class for [[TabBlocoInfo]].
 *
 * @see TabBlocoInfo
 */
class TabBlocoInfoQuery extends \projeto\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return TabBlocoInfo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TabBlocoInfo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}