<?php

namespace Codeception\Module;

use Codeception\Module;
use Codeception\Module\Percy\Exchange\Client;
use Codeception\Module\Percy\Exchange\Payload;
use Codeception\Module\Percy\InfoProvider;
use Exception;

/**
 * Class Percy
 *
 * @package Codeception\Module
 */
class Percy extends Module
{
    /**
     * Module namespace, for use with exceptions
     */
    const MODULE_NAMESPACE = 'Percy';

    /**
     * @var array
     */
    protected $config = [
        'driver' => 'WebDriver',
        'agentEndpoint' => 'http://localhost:5338',
        'agentJsPath' => 'percy-agent.js',
        'agentPostPath' => 'percy/snapshot',
        'agentConfig' => [
            'handleAgentCommunication' => false
        ]
    ];

    /**
     * @var \Codeception\Module\WebDriver
     */
    private $webDriver;

    /**
     * @var \Codeception\Module\Percy\InfoProvider
     */
    private $infoProvider;

    /**
     * @var string|null
     */
    private $percyAgentJs;

    /**
     * @inheritDoc
     * @throws \Codeception\Exception\ModuleException
     */
    public function _initialize()
    {
        /** @var \Codeception\Module\WebDriver $webDriverModule */
        $webDriverModule = $this->getModule($this->_getConfig('driver'));

        $this->webDriver = $webDriverModule;
        $this->infoProvider = InfoProvider::fromWebDriver($this->webDriver);

        try {
            $this->percyAgentJs = Client::fromUrl($this->buildUrl($this->_getConfig('agentJsPath')))->get();
        } catch (Exception $exception) {
            $this->debugSection(
                self::MODULE_NAMESPACE,
                'Cannot contact the Percy agent endpoint. Has Codeception been launched with `npx percy exec`?'
            );
        }
    }

    /**
     * Take snapshot of DOM and send to https://percy.io
     *
     * @throws \Codeception\Module\Percy\Exception\ClientException
     * @param string $name
     * @param array  $snapshotConfig
     */
    public function takeAPercySnapshot(
        string $name,
        array $snapshotConfig = []
    ) : void {
        // If we cannot access the agent JS, return silently
        if (!$this->percyAgentJs) {
            return;
        }

        // Add Percy agent JS to page
        $this->webDriver->executeJS($this->percyAgentJs);

        $payload = Payload::from(array_merge($this->_getConfig('snapshotConfig') ?? [], $snapshotConfig))
            ->withName($name)
            ->withUrl($this->webDriver->webDriver->getCurrentURL())
            ->withDomSnapshot($this->webDriver->executeJS($this->buildSnapshotJs()))
            ->withClientInfo($this->infoProvider->getClientInfo())
            ->withEnvironmentInfo($this->infoProvider->getEnvironmentInfo());

        Client::fromUrl($this->buildUrl($this->_getConfig('agentPostPath')))->withPayload($payload)->post();
    }

    /**
     * Build snapshot JS
     *
     * @return string
     */
    private function buildSnapshotJs() : string
    {
        return sprintf(
            'var percyAgentClient = new PercyAgent(%s); return percyAgentClient.snapshot(\'not used\')',
            json_encode($this->_getConfig('agentConfig'))
        );
    }

    /**
     * Build URL relative to agent endpoint
     *
     * @param string|null $path
     * @return string
     */
    private function buildUrl(?string $path = null) : string
    {
        return rtrim($this->_getConfig('agentEndpoint'), '/') . '/' . $path;
    }
}
