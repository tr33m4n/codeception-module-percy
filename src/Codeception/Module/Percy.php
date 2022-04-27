<?php

declare(strict_types=1);

namespace Codeception\Module;

use Codeception\Module;
use Codeception\Module\Percy\ConfigManagement;
use Codeception\Module\Percy\Exception\ApplicationException;
use Codeception\Module\Percy\Exchange\Payload;
use Codeception\Module\Percy\ProcessManagement;
use Codeception\Module\Percy\RequestManagement;
use Codeception\Module\Percy\ServiceContainer;
use Codeception\TestInterface;
use Exception;
use Symfony\Component\Process\Exception\RuntimeException;
use tr33m4n\CodeceptionModulePercyEnvironment\EnvironmentProviderInterface;

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

    public const PACKAGE_NAME = 'tr33m4n/codeception-module-percy';

    /**
     * @var array<string, mixed>
     */
    protected $config = [
        'snapshotBaseUrl' => 'http://localhost:5338',
        'snapshotPath' => 'percy/snapshot',
        'serializeConfig' => [
            'enableJavaScript' => true
        ],
        'snapshotConfig' => [
            'widths' => [
                375,
                1280
            ],
            'minHeight' => 1024
        ],
        'snapshotServerTimeout' => null,
        'throwOnAdapterError' => false,
        'cleanSnapshotStorage' => false
    ];

    private ?ServiceContainer $serviceContainer = null;

    private ?ConfigManagement $configManagement = null;

    private ?RequestManagement $requestManagement = null;

    private ?EnvironmentProviderInterface $environmentProvider = null;

    private ?WebDriver $webDriver = null;

    private ?string $percyCliJs = null;

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function _initialize(): void
    {
        $webDriverModule = $this->getModule('WebDriver');
        if (!$webDriverModule instanceof WebDriver) {
            throw new ApplicationException('"WebDriver" module not found');
        }

        /** @var array<string, mixed> $percyModuleConfig */
        $percyModuleConfig = $this->_getConfig() ?? [];

        $this->serviceContainer = new ServiceContainer($webDriverModule, $percyModuleConfig);
        $this->configManagement = $this->serviceContainer->getConfigManagement();
        $this->requestManagement = $this->serviceContainer->getRequestManagement();
        $this->environmentProvider = $this->serviceContainer->getEnvironmentProvider();

        $this->webDriver = $webDriverModule;
        $this->percyCliJs = file_get_contents(
            $this->serviceContainer->getConfigManagement()->getPercyCliBrowserJsPath()
        ) ?: null;
    }

    /**
     * Take snapshot of DOM and send to https://percy.io
     *
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @throws \JsonException
     * @throws \tr33m4n\CodeceptionModulePercyEnvironment\Exception\EnvironmentException
     * @param string               $name
     * @param array<string, mixed> $snapshotConfig
     */
    public function takeAPercySnapshot(
        string $name,
        array $snapshotConfig = []
    ): void {
        /**
         * As we're not "constructing" the class in a traditional sense, static analysis doesn't rule out the
         * possibility that this method could be called before `_initialize`. Check all required class properties and
         * ensure they are not "falsey"
         */
        if (!$this->percyCliJs || !$this->webDriver || !$this->webDriver->webDriver || !$this->serviceContainer || !$this->configManagement || !$this->environmentProvider) {
            return;
        }

        // Add Percy CLI JS to page
        $this->webDriver->executeJS($this->percyCliJs);

        /** @var string $domSnapshot */
        $domSnapshot = $this->webDriver->executeJS(
            sprintf('return PercyDOM.serialize(%s)', $this->configManagement->getSerializeConfig())
        );

        RequestManagement::addPayload(
            Payload::from(array_merge($this->configManagement->getSnapshotConfig(), $snapshotConfig))
                ->withName($name)
                ->withUrl($this->webDriver->webDriver->getCurrentURL())
                ->withDomSnapshot($domSnapshot)
                ->withClientInfo($this->environmentProvider->getClientInfo())
                ->withEnvironmentInfo($this->environmentProvider->getEnvironmentInfo())
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
        if (!RequestManagement::hasPayloads()) {
            return;
        }

        $this->debugSection(self::NAMESPACE, 'Sending Percy snapshots..');

        try {
            RequestManagement::sendRequest();
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
        RequestManagement::resetRequest();
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

        try {
            ProcessManagement::stopPercySnapshotServer();
        } catch (RuntimeException $exception) {
            // Fail silently if the process is not running
        }

        if ($this->configManagement && !$this->configManagement->shouldThrowOnAdapterError()) {
            return;
        }

        throw $exception;
    }
}
