<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "public.tab_jasper_parametros".
 *
 * @property integer $id
 * @property string $sgl_relid
 * @property string $cod_prestador_fk
 */
class TabJasperParametros extends base\TabJasperParametros
{
    /**
     * @inheritdoc
     * @return TabJasperParametrosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TabJasperParametrosQuery(get_called_class());
    }
}
