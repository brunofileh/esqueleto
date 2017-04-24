<?php

namespace FunctionalTester;
//use \tests\functional\BaseFunctionalTester;

class GiiSteps extends \FunctionalTester
{
    public function acessarHomePageGii()
    {
        $I = $this;
        $I->amOnPage(GiiPage::$baseUrl);
        $I->see('Welcome to Gii');
    }

    public function acessarpaginaGeracaoDeModels()
    {
        $I = $this;
        $I->amOnPage(GiiPage::$modelUrl);
        $I->see('Model Generator');
    }
    
    public function gerarModelAcessoAcao()
    {
    	$I = $this;
        $I->amOnPage(GiiPage::$modelUrl);
        $I->see('Model Generator');

        GiiPage::of($I)->gerarModel([
            'tablename'     => 'acesso.acao',
            'modelclass'    => 'Acao',
            'ns'            => 'app\modules\admin\models',
            'baseclass'     => 'projeto\db\ActiveRecord',
            'db'                => 'db',
            'usetableprefix'    => false,
            'generaterelations' => true,
            'generatelabelsfromcomments' => true,
            'generatequery' => false,
            'enablei18n'    => false,
            'template'      => 'default',
        ]);
        
        $I->seeElement('button[name="preview"]');
        $I->click('preview');
        $I->dontSeeElement('div.has-error');
        $I->seeElement('button[name="generate"]');
        $I->click(GiiPage::$generate);
    }
}