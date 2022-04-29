<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;

class ProcessManagement
{
    public const PERCY_NODE_PATH = 'PERCY_NODE_PATH';

    private ConfigManagement $configManagement;

    private ?Process $process = null;

    /**
     * ProcessManagement constructor.
     *
     * @param \Codeception\Module\Percy\ConfigManagement $configManagement
     */
    public function __construct(
        ConfigManagement $configManagement
    ) {
        $this->configManagement = $configManagement;
    }

    /**
     * Start Percy snapshot server
     *
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     */
    public function startPercySnapshotServer(): void
    {
        $this->process = new Process([
            self::resolveNodePath(),
            $this->configManagement->getPercyCliExecutablePath(),
            'exec:start'
        ]);

        $this->process->setTimeout($this->configManagement->getSnapshotServerTimeout());
        $this->process->start();

        // Wait until server is ready
        $this->process->waitUntil(fn (string $type, string $output): bool => $this->hasServerStarted($output));
    }

    /**
     * Stop Percy snapshot server
     *
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    public function stopPercySnapshotServer(): void
    {
        if (!$this->process instanceof Process || !$this->process->isRunning()) {
            throw new RuntimeException('Percy snapshot server is not running');
        }

        $this->process->stop();
    }

    /**
     * If `PERCY_NODE_PATH` has been configured, use that as the path to the Node executable, rather than what's
     * configured in `PATH`
     *
     * @return string
     */
    private function resolveNodePath(): string
    {
        return getenv(self::PERCY_NODE_PATH) ?: 'node';
    }

    /**
     * Determine whether the server has started
     *
     * @param string $cliOutput
     * @return bool
     */
    private function hasServerStarted(string $cliOutput): bool
    {
        return strpos($cliOutput, 'Percy has started!') !== false;
    }
}
