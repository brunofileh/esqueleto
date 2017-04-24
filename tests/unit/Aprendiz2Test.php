<?php

use \Codeception\Verify;
use \tests\unit\models\User;

class Aprendiz2Test extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;
    /**
     * @var \UnitTester
     */
    protected $tester;
    /**
     * Executado antes de cada teste, bom para inicializar variáveis
     */
    protected function _before()
    {
        //$this->user = User::find()->where(['login' => 'anderson'])->one();
        //$this->yii2 = $this->getModule('Yii2');
        // Yii::$app->db->open();
        // var_dump($this->yii2);
        // die();
    }
    /**
     * Executado após de cada teste, bom para destruir variáveis/objetos
     */
    protected function _after()
    {
        $this->user = null;
    }
    /**
     * testando os stubs
     */
    public function testVerificaYii2()
    {
        //$this->tester->wantTo('yada');
        //verify($this->yii2)->notNull();
        //$this->yii2->grabRecord('\tests\unit\models\User', ['login' => 'anderson']);
    }
}