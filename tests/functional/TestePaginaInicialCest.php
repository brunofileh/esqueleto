<?php

/**
 * para gerar este arquivo: 
 *   w: cept g:cest functional <NomeDoArquivoSemExtensao>
 *   l: codeception g:cest functional <NomeDoArquivoSemExtensao>
 * opcoes:
 *   --html  -> gera o arquivo _output/report.html
 *   --debug -> imprime as mensagens de debug no console. use codecept_debug($msg)
 *   --steps -> mostra as etapas de cada teste
 *   --xml   -> relatorio xml
 */

use \FunctionalTester;

class TestePaginaInicialCest
{
    // executado ANTES de cada teste, bom para inicializar/resetar variaveis
    public function _before(FunctionalTester $I)
    {
        //$I->amOnPage('/');
    }

    // executado DEPOIS de cada teste
    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function testarMalRedirecionamento404(FunctionalTester $I)
    {
        $I->amOnPage('?r=yada/pla');
        $I->see('404');
    }

    public function testarSeRedirecionaParaAPaginaLogin(FunctionalTester $I)
    {
        $I->amGoingTo('acessar a pagina inicial');
        $I->amOnPage('?r=site/index');
        $I->see('Congratulations!');

        $I->amGoingTo('acessar a pagina login');
        $I->see('Login');
        $I->click('Login');
        $I->see('Please fill out the following fields to login:');
    }

    public function testarODebug()
    {
        codecept_debug('use esta funcao para debugar variaveis');
    }
}