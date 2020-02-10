<?php

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\Exchange\Adapter\AdapterInterface;
use Codeception\Module\Percy\Exchange\Adapter\TranslatorInterface;

/**
 * Class Client
 *
 * @package Codeception\Module\Percy\Exchange
 */
final class Client implements ClientInterface
{
    /**
     * @var \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    private $adapter;

    /**
     * @var \Codeception\Module\Percy\Exchange\Payload
     */
    private $payload;

    /**
     * Client constructor.
     *
     * @param \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface $adapter
     */
    private function __construct(
        AdapterInterface $adapter
    ) {
        $this->adapter = $adapter;
    }

    /**
     * @inheritDoc
     *
     * @param \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface $adapter
     * @return \Codeception\Module\Percy\Exchange\ClientInterface
     */
    public static function fromAdapter(AdapterInterface $adapter) : ClientInterface
    {
        return new self($adapter);
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
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @param string $path
     * @return string
     */
    public function get(string $path) : string
    {
        return $this->adapter->setPath($path)->execute();
    }

    /**
     * @inheritDoc
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @param string $path
     * @return string
     */
    public function post(string $path) : string
    {
        $payloadAsString = (string) $this->payload;

        return $this->adapter->setPath($path)
            ->setIsPost()
            ->setPayload($payloadAsString)
            ->setHeaders([
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payloadAsString)
            ])->execute();
    }
}
