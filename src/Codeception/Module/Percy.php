<?php

declare(strict_types=1);

namespace Codeception\Module;

use Codeception\Module;
use Codeception\Module\Percy\Exchange\Adapter\CurlAdapter;
use Codeception\Module\Percy\Exchange\Client;
use Codeception\Module\Percy\Exchange\Payload;
use Codeception\Module\Percy\Exchange\PayloadCache;
use Codeception\Module\Percy\InfoProvider;
use Codeception\TestInterface;
use Exception;

/**
 * Class Percy
 *
 * phpcs:disable PSR2.Methods.MethodDeclaration.Underscore
 * @package Codeception\Module
 */
class Percy extends Module
{
    public const NAMESPACE = 'Percy';

    /**
     * @var array<string, mixed>
     */
    protected $config = [
        'driver' => 'WebDriver',
        'agentEndpoint' => 'http://localhost:5338',
        'agentJsPath' => 'percy-agent.js',
        'agentSnapshotPath' => 'percy/snapshot',
        'agentStopPath' => 'percy/stop',
        'agentConfig' => [
            'handleAgentCommunication' => false
        ],
        'throwOnAdapterError' => false,
        'cleanSnapshotStorageOnFail' => false,
        'cleanSnapshotStorageOnSuccess' => false
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
     * @var \Codeception\Module\Percy\Exchange\PayloadCache
     */
    private $payloadCache;

    /**
     * @var string|null
     */
    private $percyAgentJs;

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function _initialize(): void
    {
        $this->webDriver = $this->getModule($this->_getConfig('driver'));
        // Init cURL client with default adapter
        $this->client = Client::create(CurlAdapter::create($this->_getConfig('agentEndpoint')));
        $this->payloadCache = PayloadCache::create();

        try {
            $this->percyAgentJs = $this->client->get($this->_getConfig('agentJsPath'));
        } catch (Exception $exception) {
            $this->debugConnectionError($exception);
        }
    }

    /**
     * Take snapshot of DOM and send to https://percy.io
     *
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @param string               $name
     * @param array<string, mixed> $snapshotConfig
     */
    public function takeAPercySnapshot(
        string $name,
        array $snapshotConfig = []
    ): void {
        // If we cannot access the agent JS, return silently
        if (!$this->percyAgentJs) {
            return;
        }

        // Add Percy agent JS to page
        $this->webDriver->executeJS($this->percyAgentJs);

        $this->payloadCache->add(
            Payload::from(array_merge($this->_getConfig('snapshotConfig') ?? [], $snapshotConfig))
                ->withName($name)
                ->withUrl($this->webDriver->webDriver->getCurrentURL())
                ->withDomSnapshot($this->webDriver->executeJS(sprintf(
                    'var percyAgentClient = new PercyAgent(%s); return percyAgentClient.snapshot(\'not used\')',
                    json_encode($this->_getConfig('agentConfig'))
                )))
                ->withClientInfo(InfoProvider::getClientInfo())
                ->withEnvironmentInfo(InfoProvider::getEnvironmentInfo($this->webDriver))
        );
    }

    /**
     * {@inheritdoc}
     *
     * Iterate all payloads and send
     *
     * @throws \Exception
     */
    public function _afterSuite(): void
    {
        foreach ($this->payloadCache->all() as $payload) {
            $this->debugSection(
                self::NAMESPACE,
                sprintf('Sending Percy snapshot "%s"', $payload->getName())
            );

            try {
                $this->client->post($this->_getConfig('agentSnapshotPath'), $payload);
            } catch (Exception $exception) {
                $this->debugConnectionError($exception);
            }
        }

        $this->payloadCache->clear((bool) $this->_getConfig('cleanSnapshotStorageOnSuccess'));
    }

    /**
     * {@inheritdoc}
     *
     * Stop agent without sending on failure
     *
     * @throws \Exception
     * @param \Codeception\TestInterface $test
     * @param \Exception                 $fail
     */
    public function _failed(TestInterface $test, $fail): void
    {
        $this->payloadCache->clear((bool) $this->_getConfig('cleanSnapshotStorageOnFail'));

        try {
            $this->client->post($this->_getConfig('agentStopPath'));
        } catch (Exception $exception) {
            $this->debugConnectionError($exception);
        }
    }

    /**
     * Echo connection error message
     *
     * @throws \Exception
     * @param \Exception $exception
     */
    private function debugConnectionError(Exception $exception): void
    {
        $this->debugSection(
            self::NAMESPACE,
            [
                'Cannot contact the Percy agent endpoint. Has Codeception been launched with `npx percy exec`?',
                $exception->getMessage(),
                $exception->getTraceAsString()
            ]
        );

        if (!$this->_getConfig('throwOnAdapterError')) {
            return;
        }

        throw $exception;
    }
}
