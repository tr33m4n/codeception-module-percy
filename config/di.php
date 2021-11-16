<?php

use Codeception\Module\Percy\Exchange\Adapter\AdapterInterface;
use Codeception\Module\Percy\Exchange\Adapter\CurlAdapter;
use Codeception\Module\Percy\Exchange\Client;
use Codeception\Module\Percy\Exchange\ClientInterface;
use tr33m4n\Di\Container;

return [
    Container\GetPreference::CONFIG_KEY => [
        AdapterInterface::class => CurlAdapter::class,
        ClientInterface::class => Client::class
    ],
    Container\GetParameters::CONFIG_KEY => [
        CurlAdapter::class => [
            'baseUrl' => config('percy')->get('snapshotBaseUrl')
        ]
    ]
];
