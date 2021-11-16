<?php

declare(strict_types=1);

namespace Codeception\Module;

use Codeception\Module;
use Codeception\Module\Percy\Exchange\Payload;
use Codeception\Module\Percy\FilepathResolver;
use Codeception\Module\Percy\InfoProvider;
use Codeception\Module\Percy\ProcessManagement;
use Codeception\Module\Percy\RequestManagement;
use Codeception\TestInterface;
use Exception;
use Symfony\Component\Process\Exception\RuntimeException;
use tr33m4n\Utilities\Config\ConfigCollection;
use tr33m4n\Utilities\Config\ConfigProvider;
use tr33m4n\Utilities\Config\Container;

/**
 * Class Percy
 *
 * phpcs:disable PSR2.Methods.MethodDeclaration.Underscore
 *
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
        'snapshotBaseUrl' => 'http://localhost:5338',
        'snapshotPath' => 'percy/snapshot',
        'serializeConfig' => [
            'enableJavaScript' => true
        ],
        'snapshotServerTimeout' => null,
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
    private $percyCliJs;

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

        $configProvider->set('percy', ConfigCollection::from($this->_getConfig()))
            ->set('webDriver', $this->getModule($this->_getConfig('driver')));

        Container::setConfigProvider($configProvider);

        $this->webDriver = $configProvider->get('webDriver');

        /** @var \Codeception\Module\Percy\RequestManagement $requestManagementInstance */
        $requestManagementInstance = container()->get(RequestManagement::class);
        /** @var \Codeception\Module\Percy\ProcessManagement $processManagementInstance */
        $processManagementInstance = container()->get(ProcessManagement::class);

        $this->requestManagement = $requestManagementInstance;
        $this->processManagement = $processManagementInstance;

        /** @var \Codeception\Module\Percy\FilepathResolver $filepathResolver */
        $filepathResolver = container()->get(FilepathResolver::class);
        $this->percyCliJs = file_get_contents($filepathResolver->percyCliBrowserJs()) ?: null;
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
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     * @param string               $name
     * @param array<string, mixed> $snapshotConfig
     */
    public function takeAPercySnapshot(
        string $name,
        array $snapshotConfig = []
    ): void {
        // If we cannot access the CLI JS, return silently
        if (!$this->percyCliJs) {
            return;
        }

        // Add Percy CLI JS to page
        $this->webDriver->executeJS($this->percyCliJs);

        /** @var \Codeception\Module\Percy\InfoProvider $infoProvider */
        $infoProvider = container()->get(InfoProvider::class);

        $this->requestManagement->addPayload(
            Payload::from(array_merge($this->_getConfig('snapshotConfig') ?? [], $snapshotConfig))
                ->withName($name)
                ->withUrl($this->webDriver->webDriver->getCurrentURL())
                ->withDomSnapshot($this->webDriver->executeJS(
                    sprintf(
                        'return PercyDOM.serialize(%s)',
                        json_encode($this->_getConfig('serializeConfig'), JSON_THROW_ON_ERROR)
                    )
                ))
                ->withClientInfo($infoProvider->getClientInfo())
                ->withEnvironmentInfo($infoProvider->getEnvironmentInfo())
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
            $this->processManagement->stopPercySnapshotServer();
        } catch (RuntimeException $exception) {
            // Fail silently if the process is not running
        }

        throw $exception;
    }
}
