<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\Exchange\Adapter\AdapterInterface;
use Codeception\Module\Percy\Payload;

/**
 * Class Client
 *
 * @package Codeception\Module\Percy\Exchange
 */
class Client implements ClientInterface
{
    /**
     * @var \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    private $adapter;

    /**
     * Client constructor.
     *
     * @param \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface $adapter
     */
    public function __construct(
        AdapterInterface $adapter
    ) {
        $this->adapter = $adapter;
    }

    /**
     * Create new instance
     *
     * @param \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface $adapter
     * @return ClientInterface
     */
    public static function create(AdapterInterface $adapter): ClientInterface
    {
        return new self($adapter);
    }

    /**
     * @inheritDoc
     */
    public function get(string $path): string
    {
        return $this->adapter->setPath($path)->execute();
    }

    /**
     * @inheritDoc
     */
    public function post(string $path, Payload $payload = null): string
    {
        $payloadAsString = (string) $payload;

        return $this->adapter->setPath($path)
            ->setIsPost()
            ->setPayload($payloadAsString)
            ->setHeaders([
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payloadAsString)
            ])->execute();
    }
}
