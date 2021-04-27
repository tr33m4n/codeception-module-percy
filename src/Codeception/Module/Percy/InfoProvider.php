<?php

declare(strict_types=1);

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
    public const PACKAGE_NAME = 'tr33m4n/codeception-module-percy';

    /**
     * @var string|null
     */
    private static $environmentInfo;

    /**
     * @var string|null
     */
    private static $clientInfo;

    /**
     * Get environment info
     *
     * @param \Codeception\Module\WebDriver $webDriver
     * @return string
     */
    public static function getEnvironmentInfo(WebDriver $webDriver): string
    {
        if (null !== self::$environmentInfo) {
            return self::$environmentInfo;
        }

        $webDriverCapabilities = $webDriver->webDriver->getCapabilities();

        return self::$environmentInfo = sprintf(
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
    public static function getClientInfo(): string
    {
        if (null !== self::$clientInfo) {
            return self::$clientInfo;
        }

        return self::$clientInfo = sprintf(
            '%s/%s',
            ltrim(strstr(self::PACKAGE_NAME, '/') ?: '', '/'),
            strstr(Versions::getVersion(self::PACKAGE_NAME), '@', true)
        );
    }
}
