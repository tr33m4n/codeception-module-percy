<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\Exchange\Adapter\CurlAdapter;

/**
 * Class ClientFactory
 *
 * @package Codeception\Module\Percy\Exchange
 */
class ClientFactory
{
    /**
     * Create new client
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @return \Codeception\Module\Percy\Exchange\Client
     */
    public static function create(): Client
    {
        return Client::create(CurlAdapter::create(config('percy')->get('snapshotEndpoint')));
    }
}
