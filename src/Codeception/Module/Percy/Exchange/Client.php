<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\Exchange\Adapter\AdapterInterface;
use Codeception\Module\Percy\Serializer;
use Codeception\Module\Percy\Snapshot;

class Client implements ClientInterface
{
    private AdapterInterface $adapter;

    private Serializer $serializer;

    /**
     * Client constructor.
     */
    public function __construct(
        AdapterInterface $adapter,
        Serializer $serializer
    ) {
        $this->adapter = $adapter;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @throws \JsonException
     */
    public function post(string $uri, Snapshot $snapshot): string
    {
        $payloadAsString = $this->serializer->serialize($snapshot);

        return $this->adapter->setUri($uri)
            ->setPayload($payloadAsString)
            ->setHeaders([
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payloadAsString)
            ])->execute();
    }
}
