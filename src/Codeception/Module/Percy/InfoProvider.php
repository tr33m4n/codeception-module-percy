<?php

namespace Codeception\Module\Percy;

use Codeception\Module\WebDriver;

/**
 * Class InfoProvider
 *
 * @package Codeception\Module\Percy
 */
class InfoProvider
{
    /**
     * @var \Codeception\Module\WebDriver
     */
    private $webDriver;

    /**
     * @var string|null
     */
    private $environmentInfo;

    /**
     * @var string|null
     */
    private $clientInfo;

    /**
     * InfoProvider constructor.
     *
     * @param \Codeception\Module\WebDriver $webDriver
     */
    private function __construct(
        WebDriver $webDriver
    ) {
        $this->webDriver = $webDriver;
    }

    /**
     * From web drivers
     *
     * @param \Codeception\Module\WebDriver $webDriver
     * @return static
     */
    public static function fromWebDriver(WebDriver $webDriver) : self
    {
        return new self($webDriver);
    }

    /**
     * Get environment info
     *
     * @return string|null
     */
    public function getEnvironmentInfo() : ?string
    {
        if ($this->environmentInfo) {
            return $this->environmentInfo;
        }

        $webDriverCapabilities = $this->webDriver->webDriver->getCapabilities();

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
     * @return string|null
     */
    public function getClientInfo() : ?string
    {
        if ($this->clientInfo) {
            return $this->clientInfo;
        }

        $moduleInfo = json_decode(file_get_contents(__DIR__ . '/../../../../composer.json'), true);

        return $this->clientInfo = sprintf('%s/%s', explode('/', $moduleInfo['name'])[1], $moduleInfo['version']);
    }
}
