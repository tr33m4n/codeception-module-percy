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
     * <description>
     *
     * @param \Codeception\Module\WebDriver $webDriver
     * @return static
     * @author Daniel Doyle <dd@amp.co>
     */
    public static function fromWebDriver(WebDriver $webDriver) : self
    {
        return new self($webDriver);
    }

    /**
     * Get environment info
     *
     * @return string
     */
    public function getEnvironmentInfo() : string
    {
        $webDriverCapabilities = $this->webDriver->webDriver->getCapabilities();

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
    public function getClientInfo() : string
    {
        $moduleInfo = json_decode(file_get_contents(__DIR__ . '/../../../composer.json'));

        return sprintf('%s/%s', explode('/', $moduleInfo['name'])[1], $moduleInfo['version']);
    }
}
