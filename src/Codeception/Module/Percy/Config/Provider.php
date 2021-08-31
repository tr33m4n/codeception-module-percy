<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config;

use Codeception\Module\Percy\Config\CiEnvironment\CiEnvironment;
use Codeception\Module\Percy\Config\GitEnvironment\GitEnvironment;
use Codeception\Module\Percy\Config\PercyEnvironment\PercyEnvironment;
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
     * @var \Codeception\Module\Percy\Config\GitEnvironment\GitEnvironment
     */
    private $gitEnvironment;

    /**
     * @var \Codeception\Module\Percy\Config\PercyEnvironment\PercyEnvironment
     */
    private $percyEnvironment;

    /**
     * Provider constructor.
     *
     * @param \Codeception\Module\Percy\Config\CiEnvironment\CiEnvironment       $ciEnvironment
     * @param \Codeception\Module\Percy\Config\GitEnvironment\GitEnvironment     $gitEnvironment
     * @param \Codeception\Module\Percy\Config\PercyEnvironment\PercyEnvironment $percyEnvironment
     */
    public function __construct(
        CiEnvironment $ciEnvironment,
        GitEnvironment $gitEnvironment,
        PercyEnvironment $percyEnvironment
    ) {
        $this->ciEnvironment = $ciEnvironment;
        $this->gitEnvironment = $gitEnvironment;
        $this->percyEnvironment = $percyEnvironment;
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
     * Get Git environment
     *
     * @return \Codeception\Module\Percy\Config\GitEnvironment\GitEnvironment
     */
    public function getGitEnvironment(): GitEnvironment
    {
        return $this->gitEnvironment;
    }

    /**
     * Get percy environment
     *
     * @return \Codeception\Module\Percy\Config\PercyEnvironment\PercyEnvironment
     */
    public function getPercyEnvironment(): PercyEnvironment
    {
        return $this->percyEnvironment;
    }

    /**
     * Get environment info
     *
     * @param \Codeception\Module\WebDriver|null $webDriver
     * @return string
     */
    public function getEnvironmentInfo(WebDriver $webDriver = null): string
    {
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

        return implode('; ', $environmentInfo);
    }

    /**
     * Get client info
     *
     * @return string
     */
    public function getClientInfo(): string
    {
        return sprintf(
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
