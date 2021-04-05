<?php

namespace Codeception\Module\Percy;

use Codeception\Module\WebDriver;
use PackageVersions\Versions;

/**
 * Class InfoProvider
 *
 * @package Codeception\Module\Percy
 */
final class InfoProvider
{
    const PACKAGE_NAME = 'tr33m4n/codeception-module-percy';

    /**
     * Get environment info
     *
     * @param \Codeception\Module\WebDriver $webDriver
     * @return string
     */
    public static function getEnvironmentInfo(WebDriver $webDriver) : string
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
     * Get client info
     *
     * @return string
     */
    public static function getClientInfo() : string
    {
        return sprintf(
            '%s/%s',
            strstr(self::PACKAGE_NAME, '/'),
            strstr(Versions::getVersion(self::PACKAGE_NAME), '@', true)
        );
    }
}
