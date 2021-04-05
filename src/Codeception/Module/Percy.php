<?php

namespace Codeception\Module;

use Codeception\Module;
use Codeception\Module\Percy\Exchange\Adapter\CurlAdapter;
use Codeception\Module\Percy\Exchange\Client;
use Codeception\Module\Percy\Exchange\Payload;
use Codeception\Module\Percy\InfoFactory;
use Exception;

/**
 * Class Percy
 *
 * @package Codeception\Module
 */
class Percy extends Module
{
    const EXCEPTION_NAMESPACE = 'Percy';

    /**
     * @var array<string, mixed>
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
     * @var \Codeception\Module\Percy\Exchange\ClientInterface
     */
    private $client;

    /**
     * @var string|null
     */
    private $percyAgentJs;

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function _initialize() : void
    {
        $this->webDriver = $this->getModule($this->_getConfig('driver'));

        // Init cURL client with default adapter
        $this->client = Client::create(CurlAdapter::create($this->_getConfig('agentEndpoint')));

        try {
            $this->percyAgentJs = $this->client->get($this->_getConfig('agentJsPath'));
        } catch (Exception $exception) {
            $this->debugSection(
                self::EXCEPTION_NAMESPACE,
                'Cannot contact the Percy agent endpoint. Has Codeception been launched with `npx percy exec`?'
            );
        }
    }

    /**
     * Take snapshot of DOM and send to https://percy.io
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @param string               $name
     * @param array<string, mixed> $snapshotConfig
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

        $this->client
            ->setPayload(
                Payload::from(array_merge($this->_getConfig('snapshotConfig') ?? [], $snapshotConfig))
                    ->withName($name)
                    ->withUrl($this->webDriver->webDriver->getCurrentURL())
                    ->withDomSnapshot($this->webDriver->executeJS(sprintf(
                        'var percyAgentClient = new PercyAgent(%s); return percyAgentClient.snapshot(\'not used\')',
                        json_encode($this->_getConfig('agentConfig'))
                    )))
                    ->withClientInfo(InfoFactory::createClientInfo())
                    ->withEnvironmentInfo(InfoFactory::createEnvironmentInfo($this->webDriver))
            )
            ->post($this->_getConfig('agentPostPath'));
    }
}
