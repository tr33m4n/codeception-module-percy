<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\Snapshot;

interface ClientInterface
{
    /**
     * Post
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @param string                             $uri
     * @param \Codeception\Module\Percy\Snapshot $snapshot
     * @return string
     */
    public function post(string $uri, Snapshot $snapshot): string;
}
