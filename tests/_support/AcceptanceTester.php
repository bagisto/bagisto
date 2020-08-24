<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * Logging in as an Admin
     */
    public function loginAsAdmin()
    {
        $I = $this;
        $I->amOnPage('/admin');
        $I->see('Sign In');
        $I->fillField('email', 'admin@example.com');
        $I->fillField('password', 'admin123');
        $I->dontSee('The "Email" field is required.');
        $I->dontSee('The "Password" field is required.');
        $I->click('Sign In');
        $I->see('Dashboard', '//h1');
    }
}
