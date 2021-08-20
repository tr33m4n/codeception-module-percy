<?php

declare(strict_types=1);

namespace Codeception\Module;

use Codeception\Module;
use Codeception\Module\Percy\Exchange\Payload;
use Codeception\Module\Percy\RequestManagement;
use Codeception\Module\Percy\FilepathResolver;
use Codeception\Module\Percy\InfoProvider;
use Codeception\Module\Percy\ProcessManagement;
use Codeception\TestInterface;
use Exception;
use Symfony\Component\Process\Exception\RuntimeException;
use tr33m4n\Utilities\Config\Container;
use tr33m4n\Utilities\Config\ConfigProvider;

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
        'agentSnapshotPath' => 'percy/snapshot',
        'agentConfig' => [
            'handleAgentCommunication' => false
        ],
        'percyAgentTimeout' => null,
        'throwOnAdapterError' => false,
        'cleanSnapshotStorage' => false
    ];

    /**
     * @var \Codeception\Module\WebDriver
     */
    private $webDriver;

    /**
     * @var \Codeception\Module\Percy\RequestManagement
     */
    private $requestManagement;

    /**
     * @var \Codeception\Module\Percy\ProcessManagement
     */
    private $processManagement;

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
        $configProvider = new ConfigProvider([
            __DIR__ . '/../../../config'
        ]);

        $configProvider->set('percy', $this->_getConfig());

        Container::setConfigProvider($configProvider);

        $this->webDriver = $this->getModule($this->_getConfig('driver'));
        $this->requestManagement = container()->get(RequestManagement::class);
        $this->processManagement = container()->get(ProcessManagement::class);

        /** @var \Codeception\Module\Percy\FilepathResolver $filepathResolver */
        $filepathResolver = container()->get(FilepathResolver::class);
        $this->percyAgentJs = file_get_contents($filepathResolver->percyAgentBrowserJs()) ?: null;
    }

    /**
     * Take snapshot of DOM and send to https://percy.io
     *
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @throws \JsonException
     * @throws \ReflectionException
     * @throws \tr33m4n\Di\Exception\MissingClassException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @throws \tr33m4n\Utilities\Exception\ConfigException
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

        /** @var \Codeception\Module\Percy\InfoProvider $infoProvider */
        $infoProvider = container()->get(InfoProvider::class);

        $this->requestManagement->addPayload(
            Payload::from(array_merge($this->_getConfig('snapshotConfig') ?? [], $snapshotConfig))
                ->withName($name)
                ->withUrl($this->webDriver->webDriver->getCurrentURL())
                ->withDomSnapshot($this->webDriver->executeJS(sprintf(
                    'var percyAgentClient = new PercyAgent(%s); return percyAgentClient.snapshot(\'not used\')',
                    json_encode($this->_getConfig('agentConfig'), JSON_THROW_ON_ERROR)
                )))
                ->withClientInfo($infoProvider->getClientInfo())
                ->withEnvironmentInfo($infoProvider->getEnvironmentInfo($this->webDriver))
        );
    }

    /**
     * {@inheritdoc}
     *
     * Iterate all payloads and send
     *
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function _afterSuite(): void
    {
        if (!$this->requestManagement->hasPayloads()) {
            return;
        }

        $this->debugSection(self::NAMESPACE, 'Sending Percy snapshots..');

        try {
            $this->requestManagement->sendRequest();
        } catch (Exception $exception) {
            $this->debugConnectionError($exception);
        }

        $this->debugSection(self::NAMESPACE, 'All snapshots sent!');
    }

    /**
     * {@inheritdoc}
     *
     * Clear payload cache on failure
     *
     * @throws \Exception
     * @param \Codeception\TestInterface $test
     * @param \Exception                 $fail
     */
    public function _failed(TestInterface $test, $fail): void
    {
        $this->requestManagement->resetRequest();
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
            [$exception->getMessage(), $exception->getTraceAsString()]
        );

        if (!$this->_getConfig('throwOnAdapterError')) {
            return;
        }

        try {
            $this->processManagement->stopPercyAgent();
        } catch (RuntimeException $exception) {
            // Fail silently if the process is not running
        }

        throw $exception;
    }
}
