<?php 

$I = new FunctionalTester($scenario);
$I->wantTo('testar a pagina inicial');

$I->amGoingTo('acessar a pagina inicial');
$I->amOnPage('/');
$I->see('Congratulations!');

$I->amGoingTo('testar o roteador de paginas');
$I->see('Contact');
$I->click('Contact');
$I->see('If you have business inquiries or other questions, please fill out the following form to contact us.');

