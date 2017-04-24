<?php

namespace FunctionalTester;

use \FunctionalTester\LoginPage;

class PageLoginSteps extends \FunctionalTester
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
		
		$I->see('Username cannot be blank');
		$I->see('Password cannot be blank.');
	}
	
	public function tentoLogarComASenhaErrada()
	{
		$I = $this;
		
		LoginPage::of($I)->login('demo', 'demoR');
		
		$I->see('Incorrect username or password.');
	}

	public function tentoLogarComoAdmin()
	{
		$I = $this;
		
		LoginPage::of($I)->login('admin', 'admin');
		
		$I->seeCookie('_identity');
		$I->see('Congratulations');
	}

	public function tentoSairDoSistema()
	{
		$I = $this;

		$I->see('Logout (admin)');
		$I->click('Logout (admin)');
		$I->dontSeeCookie('_identity');
	}
}