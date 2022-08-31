<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;

class ProcessManagement
{
    public const PERCY_NODE_PATH = 'PERCY_NODE_PATH';

    private ConfigManagement $configManagement;

    private Debug $debug;

    private ?Process $process = null;

    /**
     * ProcessManagement constructor.
     */
    public function __construct(
        ConfigManagement $configManagement,
        Debug $debug
    ) {
        $this->configManagement = $configManagement;
        $this->debug = $debug;
    }

    /**
     * Start Percy snapshot server
     *
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     */
    public function startPercySnapshotServer(): void
    {
        if ($this->process instanceof Process && $this->process->isRunning()) {
            $this->debug->out('Snapshot server already running!');

            return;
        }

        $this->process = new Process([
            $this->resolveNodePath(),
            $this->configManagement->getPercyCliExecutablePath(),
            'exec:start',
            '-q',
            '-P',
            $this->configManagement->getSnapshotServerPort()
        ]);

        $this->process
            ->setTimeout($this->configManagement->getSnapshotServerTimeout())
            ->start(
                function (string $type, string $output): void {
                    $this->debug->out($output);
                }
            );

        $this->debug->out(
            'Snapshot server starting...',
            [
                'Node path' => realpath($this->resolveNodePath()) ?: 'Default',
                'Percy path' => realpath($this->configManagement->getPercyCliExecutablePath()) ?: '',
                'Port' => (string) $this->configManagement->getSnapshotServerPort()
            ]
        );

        // Wait until server is ready
        $running = $this->process->waitUntil(
            fn (string $type, string $output): bool => $this->hasServerStarted($output)
        );

        if (!$running) {
            $this->debug->out($this->process->getErrorOutput());

            throw new RuntimeException('Percy snapshot server is not running');
        }

        $this->debug->out('Snapshot server ready...');
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
     */
    private function resolveNodePath(): string
    {
        return getenv(self::PERCY_NODE_PATH) ?: 'node';
    }

    /**
     * Determine whether the server has started
     */
    private function hasServerStarted(string $cliOutput): bool
    {
        return strpos($cliOutput, 'Percy has started!') !== false;
    }
}
