<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config;

use Codeception\Module\Percy\Config\CiEnvironment\CiEnvironment;
use Codeception\Module\WebDriver;
use PackageVersions\Versions;

class Provider
{
    public const PACKAGE_NAME = 'tr33m4n/codeception-module-percy';

    /**
     * @var \Codeception\Module\Percy\Config\CiEnvironment\CiEnvironment
     */
    private $ciEnvironment;

    /**
     * @var string|null
     */
    private $environmentInfo;

    /**
     * @var string|null
     */
    private $clientInfo;

    /**
     * Provider constructor.
     *
     * @param \Codeception\Module\Percy\Config\CiEnvironment\CiEnvironment $ciEnvironment
     */
    public function __construct(
        CiEnvironment $ciEnvironment
    ) {
        $this->ciEnvironment = $ciEnvironment;
    }

    /**
     * Get CI environment
     *
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiEnvironment
     */
    public function getCiEnvironment(): CiEnvironment
    {
        return $this->ciEnvironment;
    }

    /**
     * Get environment info
     *
     * @param \Codeception\Module\WebDriver|null $webDriver
     * @return string
     */
    public function getEnvironmentInfo(WebDriver $webDriver = null): string
    {
        if (null !== $this->environmentInfo) {
            return $this->environmentInfo;
        }

        if (null !== $webDriver) {
            $webDriverCapabilities = $webDriver->webDriver->getCapabilities();

            $environmentInfo[] = sprintf(
                'codeception-php; %s; %s/%s',
                $webDriverCapabilities->getPlatform(),
                $webDriverCapabilities->getBrowserName(),
                $webDriverCapabilities->getVersion()
            );
        }

        $environmentInfo[] = sprintf('php/%s', phpversion());
        $environmentInfo[] = $this->getCiEnvironment()->getSlug();

        return $this->environmentInfo = implode('; ', $environmentInfo);
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

    /**
     * Get user agent
     *
     * @param \Codeception\Module\WebDriver|null $webDriver
     * @return string
     */
    public function getUserAgent(WebDriver $webDriver = null): string
    {
        return sprintf('%s (%s)', $this->getClientInfo(), $this->getEnvironmentInfo($webDriver));
    }
}
