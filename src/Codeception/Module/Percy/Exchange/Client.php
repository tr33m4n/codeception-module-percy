<?php

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\Exception\ClientException;

/**
 * Class Client
 *
 * @package Codeception\Module\Percy\Exchange
 */
final class Client implements ClientInterface
{
    /**
     * @var false|resource
     */
    private $resource;

    /**
     * Client constructor.
     *
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->resource = curl_init($url);
    }

    /**
     * @inheritDoc
     *
     * @param string $url
     * @return \Codeception\Module\Percy\Exchange\ClientInterface
     */
    public static function fromUrl(string $url) : ClientInterface
    {
        return new self($url);
    }

    /**
     * @inheritDoc
     *
     * @throws \Codeception\Module\Percy\Exception\ClientException
     * @return string
     */
    public function get() : string
    {
        curl_setopt($this->resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->resource, CURLOPT_FAILONERROR, true);

        $output = curl_exec($this->resource);
        if (curl_errno($this->resource)) {
            throw new ClientException(curl_error($this->resource));
        }

        curl_close($this->resource);

        return $output;
    }

    /**
     * @inheritDoc
     *
     * @throws \Codeception\Module\Percy\Exception\ClientException
     * @param array $payload
     * @return string
     */
    public function post(array $payload) : string
    {
        $payload = json_encode($payload);

        curl_setopt($this->resource, CURLOPT_POST, true);
        curl_setopt($this->resource, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($this->resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->resource, CURLOPT_FAILONERROR, true);
        curl_setopt($this->resource, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ]);

        $output = curl_exec($this->resource);
        if (curl_errno($this->resource)) {
            throw new ClientException(curl_error($this->resource));
        }

        curl_close($this->resource);

        return $output;
    }
}
