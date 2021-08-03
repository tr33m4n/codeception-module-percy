<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\Config\Url;
use GuzzleHttp\Client;

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
     * TODO: Validate environment configuration
     *
     * @return \GuzzleHttp\Client
     */
    public static function create() : Client
    {
        return new Client([
            'base_uri' => Url::API_BASE_URL,
            'headers' => [
                'Authorization' => sprintf('Token token=%s', $_ENV['PERCY_TOKEN'] ?? ''),
                'User-Agent' => 'TODO: see https://github.com/percy/cli/blob/4b2a4da4acafd6fd7f5e3084af0642a7eba433db/packages/client/src/client.js#L69',
                'Content-Type' => 'application/vnd.api+json'
            ]
        ]);
    }
}
