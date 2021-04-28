<?php

declare(strict_types=1);

namespace Codeception\Module;

use Codeception\Module;
use Codeception\Module\Percy\Payload;
use Codeception\Module\Percy\PayloadManagement;
use Codeception\Module\Percy\FilepathResolver;
use Codeception\Module\Percy\InfoProvider;
use Codeception\Module\Percy\ProcessManagement;
use Codeception\Module\Percy\ConfigProvider;
use Codeception\TestInterface;
use Exception;
use Symfony\Component\Process\Exception\RuntimeException;

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
        'agentStopPath' => 'percy/stop',
        'agentConfig' => [
            'handleAgentCommunication' => false
        ],
        'percyAgentTimeout' => 120,
        'throwOnAdapterError' => false,
        'cleanSnapshotStorage' => false
    ];

    /**
     * @var \Codeception\Module\WebDriver
     */
    private $webDriver;

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
        ConfigProvider::set($this->_getConfig());

        $this->webDriver = $this->getModule($this->_getConfig('driver'));
        $this->percyAgentJs = file_get_contents(FilepathResolver::percyAgentBrowserJs()) ?: null;
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

        PayloadManagement::add(
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
        $this->debugSection(self::NAMESPACE, 'Sending Percy snapshots..');

        try {
            PayloadManagement::send();
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
        PayloadManagement::clear();
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
                'Cannot contact the Percy agent endpoint. Is the Percy agent running? Has the `PERCY_TOKEN` been set?',
                $exception->getMessage(),
                $exception->getTraceAsString()
            ]
        );

        if (!$this->_getConfig('throwOnAdapterError')) {
            return;
        }

        try {
            ProcessManagement::stopPercyAgent();
        } catch (RuntimeException $exception) {
            // Fail silently if the process is not running
        }

        throw $exception;
    }
}
