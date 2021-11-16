<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;

/**
 * Class ProcessManagement
 *
 * @package Codeception\Module\Percy
 */
class ProcessManagement
{
    /**
     * @var \Codeception\Module\Percy\FilepathResolver
     */
    private $filepathResolver;

    /**
     * @var \Symfony\Component\Process\Process<string, mixed>|null
     */
    private $process;

    /**
     * ProcessManagement constructor.
     *
     * @param \Codeception\Module\Percy\FilepathResolver $filepathResolver
     */
    public function __construct(
        FilepathResolver $filepathResolver
    ) {
        $this->filepathResolver = $filepathResolver;
    }

    /**
     * Start Percy snapshot server
     *
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     */
    public function startPercySnapshotServer(): void
    {
        $this->process = new Process(['node', $this->filepathResolver->percyCliExecutable(), 'exec:start']);
        $this->process->setTimeout(config('percy')->get('snapshotServerTimeout') ?? null);
        $this->process->start();

        // Wait until server is ready
        $this->process->waitUntil(static function (string $type, string $output): bool {
            return strpos($output, 'Percy has started!') !== false;
        });
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
}
