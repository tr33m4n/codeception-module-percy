<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exception\ConfigException;
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
    private $environmentInfo;

    /**
     * @var string|null
     */
    private $clientInfo;

    /**
     * Get environment info
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @return string
     */
    public function getEnvironmentInfo(): string
    {
        if (null !== $this->environmentInfo) {
            return $this->environmentInfo;
        }

        $webDriver = config('webDriver');
        if (!$webDriver instanceof WebDriver) {
            throw new ConfigException('Web driver has not been configured');
        }

        $webDriverCapabilities = $webDriver->webDriver->getCapabilities();

        return $this->environmentInfo = sprintf(
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
    public function getClientInfo(): string
    {
        if (null !== $this->clientInfo) {
            return $this->clientInfo;
        }

        return $this->clientInfo = sprintf(
            '%s/%s',
            ltrim(strstr(self::PACKAGE_NAME, '/') ?: '', '/'),
            strstr(Versions::getVersion(self::PACKAGE_NAME), '@', true)
        );
    }
}
