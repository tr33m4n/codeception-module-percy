<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use PackageVersions\Versions;

final class InfoProvider
{
    public const PACKAGE_NAME = 'tr33m4n/codeception-module-percy';

    private static ?string $environmentInfo = null;

    private static ?string $clientInfo = null;

    /**
     * Get environment info
     *
     * @param \Facebook\WebDriver\Remote\RemoteWebDriver $webDriver
     * @return string
     */
    public static function getEnvironmentInfo(RemoteWebDriver $webDriver): string
    {
        if (null !== self::$environmentInfo) {
            return self::$environmentInfo;
        }

        $webDriverCapabilities = $webDriver->getCapabilities();

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
