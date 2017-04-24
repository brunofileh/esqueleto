<?php

namespace FunctionalTester;

class LoginPage
{
    /**
     * @var FunctionalTester;
     */
    protected $functionalTester;

    // include url of current page
    public static $URL = '?r=site/login';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    public static $username = 'input[name="LoginForm[username]"]';
    public static $password = 'input[name="LoginForm[password]"]';
    public static $remember = '#loginform-rememberme';
    public static $submit   = 'button[name="login-button"]';

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: EditPage::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }

    public function __construct(PageLoginSteps $I)
    {
        $this->functionalTester = $I;
    }

    /**
     * @return LoginPage
     */
    public static function of(PageLoginSteps $I)
    {
        return new static($I);
    }

    public function login($user, $pass, $remember=true)
    {
        $I = $this->functionalTester;
        
        $I->fillField(static::$username, $user);
        $I->fillField(static::$password, $pass);
        if ($remember) {
            $I->checkOption(static::$remember);
        }
        else {
            $I->uncheckOption(static::$remember);
        }

        $I->click(static::$submit);
    }
}