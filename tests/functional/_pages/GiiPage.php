<?php

namespace FunctionalTester;

class GiiPage extends BasePage
{
	/**
	 * Configuração de rotas (url's)
	 */
    public static $baseUrl = '?r=gii';
    public static $modelUrl = '?r=gii/default/view&id=model';
    /**
     * Mapeamento dos campos da interface (UI). CSS|XPath
     */
    public static $tablename 	= '#generator-tablename';
    public static $modelclass 	= '#generator-modelclass';
    public static $ns 			= '#generator-ns';
    public static $baseclass 	= '#generator-baseclass';
    public static $db 			= '#generator-db';
    public static $usetableprefix 	= '#generator-usetableprefix';
    public static $generaterelations= '#generator-generaterelations';
    public static $generatelabelsfromcomments = '#generator-generatelabelsfromcomments';
    public static $generatequery= '#generator-generatequery';
    public static $enablei18n 	= '#generator-enablei18n';
    public static $template 	= '#generator-template';
    public static $preview 		= 'button[name="preview"]';
    public static $generate 	= 'button[name="generate"]';
    /**
     * Ações realizadas pelo (usuário | testador)
     */
	public function gerarModel(array $config)
    {
        $I = $this->functionalTester;
        // $I->submitForm('#model-generator', [

        // ]);

        $I->fillField(static::$tablename, $config['tablename']);
        $I->fillField(static::$modelclass, $config['modelclass']);
        $I->fillField(static::$ns, $config['ns']);
        $I->fillField(static::$baseclass, $config['baseclass']);
		
		$config['generaterelations']
			? $I->checkOption(static::$generaterelations)
			: $I->uncheckOption(static::$generaterelations)
		;
		
		$config['generatelabelsfromcomments']
			? $I->checkOption(static::$generatelabelsfromcomments)
			: $I->uncheckOption(static::$generatelabelsfromcomments)
		;
		
		$config['generatequery']
			? $I->checkOption(static::$generatequery)
			: $I->uncheckOption(static::$generatequery)
		;

		$config['enablei18n']
			? $I->checkOption(static::$enablei18n)
			: $I->uncheckOption(static::$enablei18n)
		;
    }  
	
	public function gerarModel2(array $config)
    {
        $I = $this->functionalTester;
        $I->submitForm('#model-generator', [
		    'Generator[tableName]' => $config['tablename'],
		    'Generator[modelClass]' => $config['modelclass'],
		    'Generator[ns]' => $config['ns'],
		    'Generator[baseClass]' => $config['baseclass'],
		    'Generator[generateRelations]' => false,
		    'Generator[generatelabelsfromcomments]' => false,
		    'Generator[generateQuery]' => false,
		    'Generator[enablei18n]' => false,
        ], 'preview');
    }  
}