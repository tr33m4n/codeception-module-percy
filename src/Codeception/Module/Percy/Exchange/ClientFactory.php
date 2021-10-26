<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\ConfigProvider;
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
     * @return \Codeception\Module\Percy\Exchange\Client
     */
    public static function create() : Client
    {
        return Client::create(CurlAdapter::create(ConfigProvider::get('snapshotEndpoint')));
    }
}
