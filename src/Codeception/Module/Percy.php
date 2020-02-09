<?php

namespace Codeception\Module;

use Codeception\Module;
use Codeception\Module\Percy\Exception\SetupException;
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
     * @var string
     */
    private $percyAgentJs;

    /**
     * @inheritDoc
     *
     * @throws \Codeception\Exception\ModuleException
     * @throws \Codeception\Module\Percy\Exception\SetupException
     */
    public function _initialize()
    {
        $this->webDriver = $this->getModule($this->_getConfig('driver'));
        $this->infoProvider = InfoProvider::fromWebDriver($this->webDriver);

        try {
            $this->percyAgentJs = Client::fromUrl($this->buildUrl($this->_getConfig('agentJsPath')))->get();
        } catch (Exception $exception) {
            throw new SetupException(
                sprintf(
                    'Cannot contact the Percy agent endpoint. Has Codeception been launched with `npx percy exec`? %s',
                    $exception->getMessage()
                )
            );
        }
    }

    /**
     * Take snapshot of DOM and send to https://percy.io
     *
     * @param string      $name
     * @param int|null    $minHeight
     * @param string|null $percyCss
     * @param bool        $enableJavaScript
     * @param array|null  $widths
     */
    public function wantToPostAPercySnapshot(
        string $name,
        ?int $minHeight = null,
        ?string $percyCss = null,
        bool $enableJavaScript = false,
        ?array $widths = null
    ) : void {
        try {
            // Add Percy agent JS to page
            $this->webDriver->executeJS($this->percyAgentJs);

            $payload = Payload::from($this->_getConfig('snapshotConfig') ?? [])
                ->withName($name)
                ->withUrl($this->webDriver->webDriver->getCurrentURL())
                ->withPercyCss($percyCss)
                ->withMinHeight($minHeight)
                ->withDomSnapshot($this->webDriver->executeJS(
                    sprintf(
                        'var percyAgentClient = new PercyAgent(%s); return percyAgentClient.snapshot(\'not used\')',
                        json_encode($this->_getConfig('agentConfig'), true)
                    )
                ))
                ->withEnableJavascript($enableJavaScript)
                ->withClientInfo($this->infoProvider->getClientInfo())
                ->withEnvironmentInfo($this->infoProvider->getEnvironmentInfo());

            if ($widths) {
                $payload = $payload->withWidths($widths);
            }

            Client::fromUrl($this->buildUrl($this->_getConfig('agentPostPath')))->withPayload($payload)->post();
        } catch (Exception $exception) {
            $this->debugSection('percy', $exception->getMessage());
        }
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
