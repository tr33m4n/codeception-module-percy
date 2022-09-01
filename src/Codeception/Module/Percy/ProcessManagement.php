<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;

class ProcessManagement
{
    private ConfigManagement $configManagement;

    private Output $output;

    private ?Process $process = null;

    /**
     * ProcessManagement constructor.
     */
    public function __construct(
        ConfigManagement $configManagement,
        Output $output
    ) {
        $this->configManagement = $configManagement;
        $this->output = $output;
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
            $this->output->debug('Snapshot server already running!');

            return;
        }

        $command = [
            $this->configManagement->getNodePath(),
            $this->configManagement->getPercyCliExecutablePath(),
            'exec:start',
            '-P',
            $this->configManagement->getSnapshotServerPort()
        ];

        if ($this->configManagement->isDebugMode()) {
            $command[] = '-v';
        }

        $this->process = (new Process($command))
            ->setTimeout($this->configManagement->getSnapshotServerTimeout());

        $this->process->start(
            function (string $type, string $output): void {
                // Transform output to match the rest of the Codeception Percy output
                $this->output->debug(
                    str_replace('[percy', sprintf('[%s->CLI', Definitions::NAMESPACE), $output),
                    [],
                    null
                );
            }
        );

        $this->output->debug(
            'Snapshot server starting...',
            [
                'Node path' => $this->configManagement->getNodePath(),
                'Percy path' => $this->configManagement->getPercyCliExecutablePath(),
                'Port' => (string) $this->configManagement->getSnapshotServerPort()
            ]
        );

        // Wait until server is ready
        $running = $this->process->waitUntil(
            fn (string $type, string $output): bool => $this->hasServerStarted($output)
        );

        if (!$running) {
            $this->output->debug($this->process->getErrorOutput());

            throw new RuntimeException('Percy snapshot server is not running');
        }

        $this->output->debug('Snapshot server ready...');
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
     * Determine whether the server has started
     */
    private function hasServerStarted(string $cliOutput): bool
    {
        return strpos($cliOutput, 'Percy has started!') !== false;
    }
}
