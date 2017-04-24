<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "acesso.tab_sessao_php".
 *
 * @property string $id
 * @property integer $expire
 * @property resource $data
 * @property integer $user_id
 */
class TabSessaoPhp extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acesso.tab_sessao_php';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['expire', 'user_id'], 'integer'],
            [['data'], 'string'],
            [['id'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'expire' => 'Expire',
            'data' => 'Data',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @inheritdoc
     * @return TabSessaoPhpQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabSessaoPhpQuery(get_called_class());
    }
}
