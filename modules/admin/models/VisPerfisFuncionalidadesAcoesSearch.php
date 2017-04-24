<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\VisPerfisFuncionalidadesAcoes;

class VisPerfisFuncionalidadesAcoesSearch extends VisPerfisFuncionalidadesAcoes
{

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

}
