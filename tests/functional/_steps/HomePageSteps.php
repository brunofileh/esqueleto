<?php

namespace FunctionalTester;

class HomePageSteps extends \FunctionalTester
{
    public function testarHomePage()
    {
        $I = $this;
        $I->amOnPage(Page::$URL);
        $I->see('Congratulations!');
    }
}