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
     * @var \Codeception\Module\Percy\Exchange\Payload
     */
    private $payload;

    /**
     * Client constructor.
     *
     * @param string $url
     */
    private function __construct(string $url)
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
     * @param \Codeception\Module\Percy\Exchange\Payload $payload
     * @return \Codeception\Module\Percy\Exchange\ClientInterface
     */
    public function withPayload(Payload $payload) : ClientInterface
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @throws \Codeception\Module\Percy\Exception\ClientException
     * @return string
     */
    public function get() : string
    {
        curl_setopt_array($this->resource, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true
        ]);

        return $this->send();
    }

    /**
     * @inheritDoc
     *
     * @throws \Codeception\Module\Percy\Exception\ClientException
     * @return string
     */
    public function post() : string
    {
        $payloadAsString = (string) $this->payload;

        curl_setopt_array($this->resource, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payloadAsString,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payloadAsString)
            ]
        ]);

        return $this->send();
    }

    /**
     * Send request
     *
     * @throws \Codeception\Module\Percy\Exception\ClientException
     * @return string
     */
    private function send() : string
    {
        $output = curl_exec($this->resource);
        if (curl_errno($this->resource)) {
            throw new ClientException(curl_error($this->resource));
        }

        curl_close($this->resource);

        return $output;
    }
}
