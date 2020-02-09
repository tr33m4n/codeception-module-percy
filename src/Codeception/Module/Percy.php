<?php

namespace Codeception\Module;

use Codeception\Module;
use Codeception\Module\Percy\Exception\ConfigException;
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
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     * @param string      $name
     * @param array|null  $widths
     * @param int|null    $minHeight
     * @param string|null $percyCss
     * @param bool|null   $enableJavaScript
     */
    public function wantToTakeAPercySnapshot(
        string $name,
        ?array $widths = null,
        ?int $minHeight = null,
        ?string $percyCss = null,
        ?bool $enableJavaScript = null
    ) : void {
        // If we cannot access the agent JS, return silently
        if (!$this->percyAgentJs) {
            return;
        }

        // Add Percy agent JS to page
        $this->webDriver->executeJS($this->percyAgentJs);

        $payload = Payload::from($this->getSnapshotConfig())
            ->withName($name)
            ->withUrl($this->webDriver->webDriver->getCurrentURL())
            ->withDomSnapshot($this->webDriver->executeJS(
                sprintf(
                    'var percyAgentClient = new PercyAgent(%s); return percyAgentClient.snapshot(\'not used\')',
                    json_encode($this->_getConfig('agentConfig'))
                )
            ))
            ->withClientInfo($this->infoProvider->getClientInfo())
            ->withEnvironmentInfo($this->infoProvider->getEnvironmentInfo());

        if ($widths !== null) {
            $payload = $payload->withWidths($widths);
        }

        if ($minHeight !== null) {
            $payload = $payload->withMinHeight($minHeight);
        }

        if ($percyCss !== null) {
            $payload = $payload->withPercyCss($percyCss);
        }

        if ($enableJavaScript !== null) {
            $payload = $payload->withEnableJavaScript($enableJavaScript);
        }

        Client::fromUrl($this->buildUrl($this->_getConfig('agentPostPath')))->withPayload($payload)->post();
    }

    /**
     * Get snapshot config and throw an error if any blacklisted keys are found
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     * @return array
     */
    private function getSnapshotConfig() : array
    {
        $snapshotConfig = $this->_getConfig('snapshotConfig') ?? [];
        foreach (Payload::CONFIG_BLACKLIST as $blacklistKey) {
            if (!array_key_exists($blacklistKey, $snapshotConfig)) {
                continue;
            }

            throw new ConfigException(
                self::MODULE_NAMESPACE,
                sprintf('The following key is not allowed to be set through config: %s', $blacklistKey)
            );
        }

        return $snapshotConfig;
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
