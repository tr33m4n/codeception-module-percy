<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\ExecutableFinder;
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
    private static $process;

    /**
     * Start Percy agent
     *
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException
     */
    public static function startPercyAgent(): void
    {
        self::checkEnvironment();

        self::$process = new Process(['node', FilepathResolver::percyAgentExecutable(), 'start']);
        self::$process->setTimeout(ConfigProvider::get('percyAgentTimeout') ?? null);
        self::$process->start();

        sleep(5);
    }

    /**
     * Stop Percy agent
     *
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    public static function stopPercyAgent(): void
    {
        if (!self::$process instanceof Process || !self::$process->isRunning()) {
            throw new RuntimeException('Percy agent is not running');
        }

        self::$process->stop();
    }

    /**
     * If `command -v npx` throws an error, presume the `npx` command is not callable
     *
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException
     */
    private static function checkEnvironment(): void
    {
        (new Process([(new ExecutableFinder())->find('command'), '-v', 'node']))->mustRun();
    }
}
