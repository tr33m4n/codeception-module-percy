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
     * @param string                             $path
     * @param \Codeception\Module\Percy\Snapshot $snapshot
     * @return string
     */
    public function post(string $path, Snapshot $snapshot): string;
}
