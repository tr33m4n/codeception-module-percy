<?php

/**
 * Class PercyCest
 */
class PercyCest
{
    /**
     * Test basic Percy snapshot
     *
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @param \AcceptanceTester $I
     */
    public function testBasicPercySnapshot(AcceptanceTester $I)
    {
        $I->amOnPage('/page1');
        $I->takeAPercySnapshot('Page 1');
        $I->amOnPage('/page2');
        $I->takeAPercySnapshot('Page 2');
        $I->amOnPage('/page3');
        $I->takeAPercySnapshot('Page 3');
    }
}
