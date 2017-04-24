<?php

namespace AcceptanceTester;

use \AcceptanceTester\LoginPage;

class PageLoginSteps extends \AcceptanceTester
{
	public function acessoAPaginaDeLogin()
	{
		$I = $this;

		$I->amOnPage(LoginPage::$URL);
		$I->see('Login');
	}

	public function tentoLogarComUsuarioESenhaVazios()
	{
		$I = $this;
		
		LoginPage::of($I)->login('', '', false);
		
		$I->waitForText('Username cannot be blank');
		$I->waitForText('Password cannot be blank.');
	}
	
	public function tentoLogarComASenhaErrada()
	{
		$I = $this;
		
		LoginPage::of($I)->login('demo', 'demoR');
		
		$I->waitForText('Incorrect username or password.');
	}

	public function tentoLogarComoAdmin()
	{
		$I = $this;
		
		LoginPage::of($I)->login('admin', 'admin');
		
		$I->wait(3);
		$I->seeCookie('_identity');
		$I->see('Congratulations');
	}

	public function tentoSairDoSistema()
	{
		$I = $this;

		$I->see('Logout (admin)');
		$I->click('Logout (admin)');
		
		$I->wait(3);
		$I->dontSeeCookie('_identity');
	}
}