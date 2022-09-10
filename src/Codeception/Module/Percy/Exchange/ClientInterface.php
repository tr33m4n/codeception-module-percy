<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\Snapshot;

interface ClientInterface
{
    /**
     * Send snapshot
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendSnapshot(Snapshot $snapshot): void;

    /**
     * Perform health check
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function performHealthCheck(): void;
}
