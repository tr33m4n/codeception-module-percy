<?php

declare(strict_types=1);

namespace Codeception\Module;

use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Codeception\Module\Percy\ConfigManagement;
use Codeception\Module\Percy\ProcessManagement;
use Codeception\Module\Percy\ServiceContainer;
use Codeception\Module\Percy\SnapshotManagement;
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
        'snapshotServerPort' => 5338,
        'throwOnAdapterError' => false,
        'instanceId' => null,
        'collectOnly' => false
    ];

    private ConfigManagement $configManagement;

    private ProcessManagement $processManagement;

    private SnapshotManagement $snapshotManagement;

    private EnvironmentProviderInterface $environmentProvider;

    private WebDriver $webDriver;

    /**
     * Percy constructor.
     *
     * @throws \Codeception\Exception\ModuleException
     * @param array<string, mixed>|null        $config
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     */
    public function __construct(
        ModuleContainer $moduleContainer,
        array $config = null
    ) {
        parent::__construct($moduleContainer, $config);

        /** @var \Codeception\Module\WebDriver $webDriverModule */
        $webDriverModule = $this->getModule('WebDriver');

        /** @var array<string, mixed> $percyModuleConfig */
        $percyModuleConfig = $this->_getConfig() ?? [];

        $serviceContainer = new ServiceContainer($webDriverModule, $percyModuleConfig);

        $this->configManagement = $serviceContainer->getConfigManagement();
        $this->processManagement = $serviceContainer->getProcessManagement();
        $this->snapshotManagement = $serviceContainer->getSnapshotManagement();
        $this->environmentProvider = $serviceContainer->getEnvironmentProvider();
        $this->webDriver = $webDriverModule;
    }

    /**
     * Take snapshot of DOM and send to https://percy.io
     *
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @throws \JsonException
     * @throws \tr33m4n\CodeceptionModulePercyEnvironment\Exception\EnvironmentException
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     * @param string               $name
     * @param array<string, mixed> $snapshotConfig
     */
    public function takeAPercySnapshot(
        string $name,
        array $snapshotConfig = []
    ): void {
        // If the remote web driver doesn't exist, return
        if (null === $this->webDriver->webDriver) {
            return;
        }

        // Add Percy CLI JS to page
        $this->webDriver->executeJS($this->configManagement->getPercyCliBrowserJs());

        /** @var string $domString */
        $domString = $this->webDriver->executeJS(
            sprintf('return PercyDOM.serialize(%s)', $this->configManagement->getSerializeConfig())
        );

        $this->snapshotManagement->createSnapshot(
            $domString,
            $name,
            $this->webDriver->webDriver->getCurrentURL(),
            $this->environmentProvider->getClientInfo(),
            $this->environmentProvider->getEnvironmentInfo(),
            array_merge($this->configManagement->getSnapshotConfig(), $snapshotConfig)
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
        if ($this->configManagement->shouldCollectOnly()) {
            $this->debugSection(self::NAMESPACE, 'All snapshots collected!');

            return;
        }

        try {
            $this->snapshotManagement->sendAll();
        } catch (Exception $exception) {
            $this->debugConnectionError($exception);
        }
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
        $this->snapshotManagement->reset();
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
            $this->processManagement->stopPercySnapshotServer();
        } catch (RuntimeException $exception) {
            // Fail silently if the process is not running
        }

        if (!$this->configManagement->shouldThrowOnAdapterError()) {
            return;
        }

        throw $exception;
    }
}
