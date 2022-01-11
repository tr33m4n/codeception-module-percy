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
    public const PERCY_NODE_PATH = 'PERCY_NODE_PATH';

    /**
     * @var \Symfony\Component\Process\Process<string, mixed>|null
     */
    private static $process;

    /**
     * Start Percy snapshot server
     *
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException
     */
    public static function startPercySnapshotServer(): void
    {
        self::$process = new Process([self::resolveNodePath(), FilepathResolver::percyCliExecutable(), 'exec:start']);
        self::$process->setTimeout(ConfigProvider::get('snapshotServerTimeout') ?? null);
        self::$process->start();

        // Wait until server is ready
        self::$process->waitUntil(static function (string $type, string $output): bool {
            return strpos($output, 'Percy has started!') !== false;
        });
    }

    /**
     * Stop Percy snapshot server
     *
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    public static function stopPercySnapshotServer(): void
    {
        if (!self::$process instanceof Process || !self::$process->isRunning()) {
            throw new RuntimeException('Percy snapshot server is not running');
        }

        self::$process->stop();
    }

    /**
     * If `PERCY_NODE_PATH` has been configured, use that as the path to the Node executable, rather than what's
     * configured in `PATH`
     *
     * @return string
     */
    private static function resolveNodePath(): string
    {
        return $_ENV[self::PERCY_NODE_PATH] ?? 'node';
    }
}
