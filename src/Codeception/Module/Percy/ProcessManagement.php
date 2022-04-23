<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;

class ProcessManagement
{
    public const PERCY_NODE_PATH = 'PERCY_NODE_PATH';

    private static ?Process $process = null;

    /**
     * Start Percy snapshot server
     *
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException
     */
    public static function startPercySnapshotServer(): void
    {
        /** @var float|int|null $snapshotServerTimeout */
        $snapshotServerTimeout = ConfigProvider::get('snapshotServerTimeout') ?? null;

        self::$process = new Process([self::resolveNodePath(), FilepathResolver::percyCliExecutable(), 'exec:start']);
        self::$process->setTimeout($snapshotServerTimeout);
        self::$process->start();

        // Wait until server is ready
        self::$process->waitUntil(static fn (string $type, string $output): bool =>
            strpos($output, 'Percy has started!') !== false);
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
        return getenv(self::PERCY_NODE_PATH) ?: 'node';
    }
}
