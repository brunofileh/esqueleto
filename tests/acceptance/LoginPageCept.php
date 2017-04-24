<?php 

use \AcceptanceTester\PageLoginSteps;

$I = new PageLoginSteps($scenario);
$I->wantTo('testar o sistema de login');

$I->amGoingTo('== Acessar a pagina de login ==');
$I->acessoAPaginaDeLogin();

$I->amGoingTo('== tentar logar com LOGIN e SENHA vazios ==');
$I->tentoLogarComUsuarioESenhaVazios();

$I->amGoingTo('== tentar logar com SENHA incorreta ==');
$I->tentoLogarComASenhaErrada();

$I->amGoingTo('== tentar logar como administrador ==');
$I->tentoLogarComoAdmin();

$I->amGoingTo('== tentar sair do sistema ==');
$I->tentoSairDoSistema();