<?php

/**
 * Class PercyCest
 */
class PercyCest
{
    /**
     * Test basic Percy snapshot
     *
     * @param \AcceptanceTester $I
     * @throws \Throwable
     * @throws \Codeception\Module\Percy\Exception\StorageException
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
