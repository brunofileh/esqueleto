<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rlc_modulos_prestadores".
 *
 * @property integer $cod_modulo_prestador
 * @property integer $cod_prestador_fk
 * @property integer $cod_modulo_fk
 * @property string $cp001
 * @property string $cp002
 * @property string $cp003
 * @property string $cp004
 * @property string $cp005
 * @property string $cp006
 * @property string $cp007
 * @property string $cp008
 * @property string $cp009
 * @property string $cp010
 * @property string $cp011
 * @property string $cp012
 * @property string $cp013
 * @property string $cp014
 * @property string $cp015
 * @property string $cp016
 * @property string $cp017
 * @property string $cp018
 * @property integer $cp019
 * @property string $cp021
 * @property string $cp022
 * @property string $cp023
 * @property string $cp024
 * @property string $cp025
 * @property string $cp026
 * @property string $cp028
 * @property string $cp029
 * @property integer $cp030
 * @property integer $cp031
 * @property string $cp032
 * @property string $cp033
 * @property integer $cp034
 * @property string $cp036
 * @property string $cp037
 * @property string $cp038
 * @property string $cp039
 * @property string $cp040
 * @property string $cp041
 * @property string $cp042
 * @property string $cp043
 * @property integer $cp044
 * @property integer $cp045
 * @property string $cp046
 * @property string $cp047
 * @property integer $cp048
 * @property integer $cp049
 * @property string $cp050
 * @property string $cp051
 * @property string $cp052
 * @property string $cp053
 * @property string $cp054
 * @property string $cp055
 * @property string $cp056
 * @property string $cp057
 * @property integer $cp058
 * @property integer $cp059
 * @property integer $cp060
 * @property integer $cp061
 * @property integer $cp062
 * @property string $cp063
 * @property string $txt_login_inclusao
 * @property string $dte_inclusao
 * @property string $dte_alteracao
 * @property string $dte_exclusao
 * @property string $cod_municipio_fk
 * @property string $cp064
 *
 * @property TabParticipacoes[] $tabParticipacoes
 * @property TabModulos $tabModulos
 * @property TabPrestadores $tabPrestadores
 */
class RlcModulosPrestadores extends \projeto\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rlc_modulos_prestadores';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod_prestador_fk', 'cod_modulo_fk'], 'required'],
            [['cod_prestador_fk', 'cod_modulo_fk', 'cp019', 'cp030', 'cp031', 'cp034', 'cp044', 'cp045', 'cp048', 'cp049', 'cp058', 'cp059', 'cp060', 'cp061', 'cp062'], 'integer'],
            [['dte_inclusao', 'dte_alteracao', 'dte_exclusao'], 'safe'],
            [['cp001', 'cp003', 'cp017', 'cp018', 'cp032', 'cp033', 'cp046', 'cp047'], 'string', 'max' => 100],
            [['cp002'], 'string', 'max' => 10],
            [['cp004'], 'string', 'max' => 8],
            [['cp005', 'cp006'], 'string', 'max' => 50],
            [['cp007'], 'string', 'max' => 70],
            [['cp008', 'cp010', 'cp012', 'cp014', 'cp021', 'cp023', 'cp025', 'cp036', 'cp038', 'cp040', 'cp050', 'cp052', 'cp054'], 'string', 'max' => 15],
            [['cp009', 'cp011', 'cp013', 'cp022', 'cp024', 'cp026', 'cp037', 'cp039', 'cp041', 'cp051', 'cp053', 'cp055'], 'string', 'max' => 5],
            [['cp015', 'cp016', 'cp028', 'cp029', 'cp042', 'cp043', 'cp056', 'cp057', 'txt_login_inclusao'], 'string', 'max' => 150],
            [['cp063'], 'string', 'max' => 2000],
            [['cod_municipio_fk'], 'string', 'max' => 6],
            [['cp064'], 'string', 'max' => 20],
            [['cod_prestador_fk', 'cod_modulo_fk'], 'unique', 'targetAttribute' => ['cod_prestador_fk', 'cod_modulo_fk'], 'message' => 'The combination of Codigo do prestador and Cod Modulo Fk has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_modulo_prestador' => 'Código do Modulo Prestador',
            'cod_prestador_fk' => 'Codigo do prestador',
            'cod_modulo_fk' => 'Cod Modulo Fk',
            'cp001' => 'Nome da Secretaria/Departam/Setor responsavel pelo servico de Drenagem e Manejo de Águas Pluviais no municipio',
            'cp002' => 'CEP da secretaria',
            'cp003' => 'Endereço da secretaria',
            'cp004' => 'Número da secretaria',
            'cp005' => 'Complemento da secretaria',
            'cp006' => 'Bairro da secretaria',
            'cp007' => 'Site da secretaria',
            'cp008' => 'Telefone da secretaria',
            'cp009' => 'Ramal da secretaria',
            'cp010' => 'Telefone 2 da secretaria',
            'cp011' => 'Ramal 2 da secretaria',
            'cp012' => 'Fax da secretaria',
            'cp013' => 'Ramal Fax da secretaria',
            'cp014' => 'Telefone 3 da secretaria',
            'cp015' => 'E-mail da secretaria',
            'cp016' => 'E-mail 2 da secretaria',
            'cp017' => 'Nome do responsável',
            'cp018' => 'Cargo do responsável',
            'cp019' => 'Gênero do responsável',
            'cp021' => 'Telefone do responsável',
            'cp022' => 'Ramal do responsável',
            'cp023' => 'Telefone 2 do responsável',
            'cp024' => 'Ramal 2 do responsável',
            'cp025' => 'Fax do responsável',
            'cp026' => 'Ramal Fax do responsável',
            'cp028' => 'E-mail do responsável',
            'cp029' => 'E-mail 2 do responsável',
            'cp030' => 'Partido do responsável',
            'cp031' => 'Sucessão do responsável',
            'cp032' => 'Nome do encarregado',
            'cp033' => 'Cargo do encarregado',
            'cp034' => 'Gênero do encarregado',
            'cp036' => 'Telefone do encarregado',
            'cp037' => 'Ramal do encarregado',
            'cp038' => 'Telefone 2  do encarregado',
            'cp039' => 'Ramal 2 do encarregado',
            'cp040' => 'Fax do encarregado',
            'cp041' => 'Ramal Fax do encarregado',
            'cp042' => 'E-mail do encarregado',
            'cp043' => 'E-mail 2 do encarregado',
            'cp044' => 'Partido do encarregado',
            'cp045' => 'Sucessão do encarregado',
            'cp046' => 'Nome do encarregado substituto',
            'cp047' => 'Cargo do encarregado substituto',
            'cp048' => 'Gênero do encarregado substituto',
            'cp049' => 'Tratamento do encarregado substituto',
            'cp050' => 'Telefone do encarregado substituto',
            'cp051' => 'Ramal do encarregado substituto',
            'cp052' => 'Telefone 2 do encarregado substituto',
            'cp053' => 'Ramal 2 do encarregado substituto',
            'cp054' => 'Fax do encarregado substituto',
            'cp055' => 'Ramal fax do encarregado substituto',
            'cp056' => 'E-mail do encarregado substituto',
            'cp057' => 'E-mail 2 do encarregado substituto',
            'cp058' => 'Partido do encarregado substituto',
            'cp059' => 'Sucessão do encarregado substituto',
            'cp060' => 'Último ano em que o prestador foi convidado para coleta de dados antes de ser excluído.',
            'cp061' => 'Existe(m) outro(s) órgão(s) ou entidade(s), além da Prefeitura, responsável(is) pelos serviços de Drenagem e Manejo de Águas Pluviais no município?',
            'cp062' => 'prefeitura presta o serviço',
            'cp063' => 'Observações',
            'txt_login_inclusao' => 'Usuário da Inclusão',
            'dte_inclusao' => 'Data da Inclusão',
            'dte_alteracao' => 'Data da Alteração',
            'dte_exclusao' => 'Data da exclusao',
            'cod_municipio_fk' => 'Código do município da secretaria',
            'cp064' => 'CNPJ secretaria',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabParticipacoes()
    {
        return $this->hasMany(TabParticipacoes::className(), ['cod_modulo_prestador_fk' => 'cod_modulo_prestador']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabModulos()
    {
        return $this->hasOne(TabModulos::className(), ['cod_modulo' => 'cod_modulo_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTabPrestadores()
    {
        return $this->hasOne(TabPrestadores::className(), ['cod_prestador' => 'cod_prestador_fk']);
    }

    /**
     * @inheritdoc
     * @return RlcModulosPrestadoresQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RlcModulosPrestadoresQuery(get_called_class());
    }
}
