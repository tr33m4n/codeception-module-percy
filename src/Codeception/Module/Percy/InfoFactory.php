<?php

namespace Codeception\Module\Percy;

use Codeception\Module\WebDriver;

/**
 * Class InfoFactory
 *
 * @package Codeception\Module\Percy
 */
final class InfoFactory
{
    /**
     * Create environment info
     *
     * @param WebDriver $webDriver
     * @return string
     */
    public static function createEnvironmentInfo(WebDriver $webDriver) : string
    {
        $webDriverCapabilities = $webDriver->webDriver->getCapabilities();

        return sprintf(
            'codeception-php; %s; %s/%s',
            $webDriverCapabilities->getPlatform(),
            $webDriverCapabilities->getBrowserName(),
            $webDriverCapabilities->getVersion()
        );
    }

    /**
     * Create client info
     *
     * @return string
     */
    public static function createClientInfo() : string
    {
        $moduleInfo = json_decode(file_get_contents(__DIR__ . '/../../../../composer.json'), true);

        return sprintf('%s/%s', explode('/', $moduleInfo['name'])[1], $moduleInfo['version']);
    }
}
