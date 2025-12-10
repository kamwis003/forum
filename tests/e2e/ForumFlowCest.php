<?php
class ForumFlowCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/viewforum.php');
    }

    public function guestCannotCreateTopic(AcceptanceTester $I)
    {
        $I->see('Utwórz temat');
        $I->dontSeeElement('input[name=submit]:not([disabled])');
    }

    public function userCanCreateTopic(AcceptanceTester $I)
    {
        $I->loginAs('testuser', 'testpassword'); // funkcja login w helperach
        $I->fillField('tname', 'Nowy testowy wątek');
        $I->fillField('message', 'Testowa wiadomość');
        $I->click('Utwórz');
        $I->see('Nowy testowy wątek');
    }
}
