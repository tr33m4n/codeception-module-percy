<?php

namespace Codeception\Module\Percy\Exchange;

/**
 * Class Client
 *
 * @package Codeception\Module\Percy\Exchange
 */
final class Client implements ClientInterface
{
    /**
     * @var \Codeception\Module\Percy\Exchange\AdapterInterface
     */
    private $adapter;

    /**
     * @var \Codeception\Module\Percy\Exchange\Payload
     */
    private $payload;

    /**
     * Client constructor.
     *
     * @param string                                                   $url
     * @param \Codeception\Module\Percy\Exchange\AdapterInterface|null $adapter
     */
    private function __construct(
        string $url,
        ?AdapterInterface $adapter = null
    ) {
        $this->adapter = $adapter ? $adapter->setUrl($url) : new CurlAdapter($url);
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
        $this->adapter->setOptions([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true
        ]);

        return $this->adapter->execute();
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

        $this->adapter->setOptions([
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payloadAsString,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payloadAsString)
            ]
        ]);

        return $this->adapter->execute();
    }
}
