<?php 

$I = new \FunctionalTester\GiiSteps($scenario);
$I->wantTo('Testar o Gii');

$I->amGoingTo('Acessar a pagina inicial do Gii');
$I->acessarHomePageGii();

$I->amGoingTo('Acessar a pagina de geracao de models');
$I->acessarpaginaGeracaoDeModels();

$I->amGoingTo('Gerar a model acesso.acao');
$I->gerarModelAcessoAcao();
