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
     * @var \Symfony\Component\Process\Process<string, mixed>|null
     */
    private $process;

    /**
     * Start Percy agent
     *
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException
     */
    public function startPercyAgent(): void
    {
        $this->process = new Process(['node', FilepathResolver::percyAgentExecutable(), 'start']);
        $this->process->setTimeout(ConfigProvider::get('percyAgentTimeout') ?? null);
        $this->process->start();

        sleep(5);
    }

    /**
     * Stop Percy agent
     *
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    public function stopPercyAgent(): void
    {
        if (!$this->process instanceof Process || !$this->process->isRunning()) {
            throw new RuntimeException('Percy agent is not running');
        }

        $this->process->stop();
    }
}
