<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Action;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Utils;

abstract class AbstractAction
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $client;

    /**
     * CreateBuild constructor.
     *
     * @param \GuzzleHttp\ClientInterface $client
     */
    public function __construct(
        ClientInterface $client
    ) {
        $this->client = $client;
    }

    /**
     * Execute action
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return mixed
     */
    abstract public function execute();

    /**
     * Send GET request and return as JSON
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @param string               $uri
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    protected function get(string $uri, array $options = []): array
    {
        return $this->requestAsJson('GET', $uri, $options);
    }

    /**
     * Send POST request and return as JSON
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @param string               $uri
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    protected function post(string $uri, array $options = []): array
    {
        return $this->requestAsJson('POST', $uri, $options);
    }

    /**
     * Request as JSON
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @param string               $method
     * @param string               $uri
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    private function requestAsJson(string $method, string $uri = '', array $options = []): array
    {
        return (array) Utils::jsonDecode((string) $this->client->request($method, $uri, $options)->getBody());
    }
}
