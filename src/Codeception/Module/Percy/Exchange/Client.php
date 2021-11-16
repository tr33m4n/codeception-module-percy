<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Utils;
use tr33m4n\CodeceptionModulePercyEnvironment\EnvironmentProvider;

class Client
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $client;

    /**
     * @var \tr33m4n\CodeceptionModulePercyEnvironment\EnvironmentProvider
     */
    private $environmentProvider;

    /**
     * CreateBuild constructor.
     *
     * @param \GuzzleHttp\ClientInterface                                    $client
     * @param \tr33m4n\CodeceptionModulePercyEnvironment\EnvironmentProvider $environmentProvider
     */
    public function __construct(
        ClientInterface $client,
        EnvironmentProvider $environmentProvider
    ) {
        $this->client = $client;
        $this->environmentProvider = $environmentProvider;
    }

    /**
     * Send GET request and return as JSON
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @param string               $uri
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    public function get(string $uri, array $options = []): array
    {
        return $this->responseAsArray('GET', $uri, $this->withDefaultHeaders($options));
    }

    /**
     * Send POST request and return as JSON
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @param string               $uri
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    public function post(string $uri, array $data = [], array $options = []): array
    {
        return $this->responseAsArray(
            'POST',
            $uri,
            $this->withDefaultHeaders(
                array_merge_recursive([
                    RequestOptions::HEADERS => [
                        'Content-Type' => 'application/vnd.api+json'
                    ],
                    RequestOptions::JSON => $data
                ], $options)
            )
        );
    }

    /**
     * Response as array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @param string               $method
     * @param string               $uri
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    private function responseAsArray(string $method, string $uri = '', array $options = []): array
    {
        return (array) Utils::jsonDecode((string) $this->client->request($method, $uri, $options)->getBody());
    }

    /**
     * With default headers
     *
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    private function withDefaultHeaders(array $options): array
    {
        return array_merge_recursive(
            [
                RequestOptions::HEADERS => [
                    'Authorization' => sprintf('Token token=%s', $_ENV['PERCY_TOKEN'] ?? ''),
                    'User-Agent' => $this->environmentProvider->getUserAgent()
                ]
            ],
            $options
        );
    }
}
