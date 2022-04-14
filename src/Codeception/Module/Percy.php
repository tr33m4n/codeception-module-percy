<?php

declare(strict_types=1);

namespace Codeception\Module;

use Codeception\Module;
use Codeception\Module\Percy\ConfigProvider;
use Codeception\Module\Percy\Exception\ApplicationException;
use Codeception\Module\Percy\Exchange\Payload;
use Codeception\Module\Percy\FilepathResolver;
use Codeception\Module\Percy\InfoProvider;
use Codeception\Module\Percy\ProcessManagement;
use Codeception\Module\Percy\RequestManagement;
use Codeception\TestInterface;
use Exception;
use Symfony\Component\Process\Exception\RuntimeException;

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

    private ?WebDriver $webDriver = null;

    private ?string $percyCliJs = null;

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function _initialize(): void
    {
        ConfigProvider::set($this->_getConfig());

        $webDriverModule = $this->getModule('WebDriver');
        if (!$webDriverModule instanceof WebDriver) {
            throw new ApplicationException('"WebDriver" module not found');
        }

        $this->webDriver = $webDriverModule;
        $this->percyCliJs = file_get_contents(FilepathResolver::percyCliBrowserJs()) ?: null;
    }

    /**
     * Take snapshot of DOM and send to https://percy.io
     *
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @throws \JsonException
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

        // If web driver has not been set, return
        if (null === $this->webDriver) {
            return;
        }

        // If remote web driver has not been set, return
        if (null === $this->webDriver->webDriver) {
            return;
        }

        // Add Percy CLI JS to page
        $this->webDriver->executeJS($this->percyCliJs);

        RequestManagement::addPayload(
            Payload::from(array_merge($this->_getConfig('snapshotConfig') ?? [], $snapshotConfig))
                ->withName($name)
                ->withUrl($this->webDriver->webDriver->getCurrentURL())
                ->withDomSnapshot($this->webDriver->executeJS(
                    sprintf(
                        'return PercyDOM.serialize(%s)',
                        json_encode($this->_getConfig('serializeConfig'), JSON_THROW_ON_ERROR)
                    )
                ))
                ->withClientInfo(InfoProvider::getClientInfo())
                ->withEnvironmentInfo(InfoProvider::getEnvironmentInfo($this->webDriver->webDriver))
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

        if (!$this->_getConfig('throwOnAdapterError')) {
            return;
        }

        try {
            ProcessManagement::stopPercySnapshotServer();
        } catch (RuntimeException $exception) {
            // Fail silently if the process is not running
        }

        throw $exception;
    }
}
