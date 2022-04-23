<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\ConfigProvider;
use Codeception\Module\Percy\Exception\ConfigException;
use Codeception\Module\Percy\Exchange\Adapter\CurlAdapter;

class ClientFactory
{
    /**
     * Create new client
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     * @return \Codeception\Module\Percy\Exchange\Client
     */
    public static function create(): Client
    {
        /** @var string $snapshotBaseUrl */
        $snapshotBaseUrl = ConfigProvider::get('snapshotBaseUrl');
        if (!filter_var($snapshotBaseUrl, FILTER_VALIDATE_URL)) {
            throw new ConfigException('Snapshot base URL is not a valid URL');
        }

        return Client::create(CurlAdapter::create($snapshotBaseUrl));
    }
}
