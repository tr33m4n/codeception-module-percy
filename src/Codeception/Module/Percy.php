<?php

declare(strict_types=1);

namespace Codeception\Module;

use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Codeception\Module\Percy\ConfigManagement;
use Codeception\Module\Percy\Definitions;
use Codeception\Module\Percy\Exception\PercyDisabledException;
use Codeception\Module\Percy\Output;
use Codeception\Module\Percy\ProcessManagement;
use Codeception\Module\Percy\ServiceContainer;
use Codeception\Module\Percy\SnapshotManagement;
use Codeception\Module\Percy\ValidateEnvironment;
use Codeception\TestInterface;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Symfony\Component\Process\Exception\RuntimeException;
use Throwable;
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
    private ConfigManagement $configManagement;

    private ProcessManagement $processManagement;

    private SnapshotManagement $snapshotManagement;

    private EnvironmentProviderInterface $environmentProvider;

    private ValidateEnvironment $validateEnvironment;

    private Output $output;

    private WebDriver $webDriver;

    /**
     * Percy constructor.
     *
     * @param array<string, mixed>|null $config
     * @throws \Codeception\Exception\ModuleException
     */
    public function __construct(
        ModuleContainer $moduleContainer,
        array $config = null
    ) {
        // Set within the constructor, so we can support Codeception 4 and 5 typed properties
        $this->config = Definitions::DEFAULT_CONFIG;

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
        $this->validateEnvironment = $serviceContainer->getValidateEnvironment();
        $this->output = $serviceContainer->getOutput();
        $this->webDriver = $webDriverModule;
    }

    /**
     * Take snapshot of DOM and send to https://percy.io
     *
     * @param array<string, mixed> $snapshotConfig
     * @throws \Throwable
     */
    public function takeAPercySnapshot(
        string $name,
        array $snapshotConfig = []
    ): void {
        // If the remote web driver doesn't exist, return
        if (!$this->webDriver->webDriver instanceof RemoteWebDriver) {
            return;
        }

        try {
            $this->validateEnvironment->execute();

            // Add Percy CLI JS to page
            $this->webDriver->executeJS($this->configManagement->getPercyCliBrowserJs());

            /** @var string|array{html?: string} $domString */
            $domString = $this->webDriver->executeJS(
                sprintf('return PercyDOM.serialize(%s)', $this->configManagement->getSerializeConfig())
            );

            $this->snapshotManagement->createSnapshot(
                is_array($domString) ? $domString['html'] ?? '' : $domString,
                $name,
                $this->webDriver->webDriver->getCurrentURL(),
                $this->environmentProvider->getClientInfo(),
                $this->environmentProvider->getEnvironmentInfo(),
                array_merge($this->configManagement->getSnapshotConfig(), $snapshotConfig)
            );
        } catch (Throwable $exception) {
            $this->onError($exception);
        }
    }

    /**
     * {@inheritdoc}
     *
     * Iterate all payloads and send
     *
     * @throws \Throwable
     */
    public function _afterSuite(): void
    {
        try {
            $this->validateEnvironment->execute();

            if ($this->configManagement->shouldCollectOnly()) {
                $this->output->debug('All snapshots collected!');

                return;
            }

            $this->snapshotManagement->sendInstance();
        } catch (Throwable $exception) {
            $this->onError($exception);
        }
    }

    /**
     * {@inheritdoc}
     *
     * Clear payload cache on failure
     *
     * @param \Exception $fail
     * @throws \Throwable
     */
    public function _failed(TestInterface $test, $fail): void
    {
        try {
            $this->validateEnvironment->execute();

            $this->snapshotManagement->resetInstance();
        } catch (Throwable $exception) {
            $this->onError($exception);
        }
    }

    /**
     * On error
     *
     * @throws \Throwable
     */
    private function onError(Throwable $exception): void
    {
        // Always error silently if it's a "Percy disabled" exception
        if ($exception instanceof PercyDisabledException) {
            $this->output->debug($exception);

            return;
        }

        try {
            $this->processManagement->stopPercySnapshotServer();
        } catch (RuntimeException $runtimeException) {
            // Fail silently if the process is not running
        }

        if (!$this->configManagement->shouldThrowOnError()) {
            $this->output->debug($exception->getMessage(), ['Trace' => $exception->getTraceAsString()]);

            return;
        }

        throw $exception;
    }
}
