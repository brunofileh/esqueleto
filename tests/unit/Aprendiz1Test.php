<?php

use \Codeception\Verify;
use \Codeception\Util\Stub;

class Aprendiz1Test extends \Codeception\TestCase\Test
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
        $this->numero = 10;
        $this->cidade = 'Brasília/DF';
        $this->nome = 'Maria';
        $this->papeis = ['admin', 'moderador'];
        $this->valorNulo = null;
        $this->arrayVazio = [];
        $this->verdadeiro = true;
        $this->falso = false;
        $this->array = ['a' => 'b', 'c' => 'd'];

        /**
         * Stubs premitem adicionar métodos e propriedades a uma classe existente
         * @see http://codeception.com/docs/reference/Stub
         */
        $this->user = Stub::make('stdClass', ['getName' => 'john']);
    }
    /**
     * Executado após de cada teste, bom para destruir variáveis/objetos
     */
    protected function _after()
    {
        $this->numero = null;
        // unset($this->obj);
    }
    /**
     * testes comecam daqui p/abaixo
     * Este aqui é apenas um exemplo de como proceder
     */
    public function testBasicoSpecifyVerify()
    {
        $this->specify('Escreva aqui a especificação do teste', function() {
            // faça o teste e verifique se o mesmo passa
            verify($this->numero)->equals(10);
            // outra maneira:
            // $this->assertEquals($this->numero, 10);
        });
    }
    /**
     * testa o escopo das variáveis
     */
    public function testEscopoDasVariaveis()
    {
        $this->specify('altera uma variável global no escopo local', function() {
            // aqui é feito um clone da variável apenas neste escopo
            $this->numero = 20;
            verify($this->numero)->equals(20);
        });

        $this->specify('aqui a variável volta ao seu valor anterior', function() {
            verify($this->numero)->equals(10);
        });
    }
    /**
     * Funcionalidades básicas dos testes
     */
    public function testFuncionalidadesBasicas()
    {
        $this->specify('testando igualdade/diferença', function () {
            verify($this->cidade)->equals('Brasília/DF');
            verify($this->nome)->notEquals('Joana');
        });

        $this->specify('testando se um objeto está contido em uma lista/array', function () {
            verify($this->papeis)->contains('moderador');
            verify($this->papeis)->notContains('visitante');
        });

        $this->specify('testando maior/menor', function () {
            // deveria ser "lessThan" e não "lessThen"
            verify($this->numero)->lessThen(12); // menor que
            verify($this->numero)->greaterThan(5); // maior que
            verify($this->numero)->lessOrEquals(11); // menor ou igual
            verify($this->numero)->greaterOrEquals(9); // maior ou igual
        });

        $this->specify('testando true/false/null', function () {
            verify($this->verdadeiro)->true();
            verify($this->falso)->false();
            verify($this->valorNulo)->null();
            verify($this->nome)->notNull();
        });

        $this->specify('testando objetos vazios', function () {
            verify($this->arrayVazio)->isEmpty();
            verify($this->array)->notEmpty();
        });

        $this->specify('testando chaves em array', function () {
            verify($this->array)->hasKey('a'); // array possui a chave 'b'
            verify($this->array)->hasntKey('e'); // array não possui a chave 'e'
        });
    }
}