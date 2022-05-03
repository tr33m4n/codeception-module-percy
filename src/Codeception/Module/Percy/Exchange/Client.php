<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\Exchange\Adapter\AdapterInterface;
use Codeception\Module\Percy\Snapshot;

class Client implements ClientInterface
{
    private AdapterInterface $adapter;

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
     * {@inheritdoc}
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @throws \JsonException
     * @param string                             $path
     * @param \Codeception\Module\Percy\Snapshot $snapshot
     * @return string
     */
    public function post(string $path, Snapshot $snapshot): string
    {
        $payloadAsString = json_encode($snapshot, JSON_THROW_ON_ERROR);

        return $this->adapter->setPath($path)
            ->setPayload($payloadAsString)
            ->setHeaders([
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payloadAsString)
            ])->execute();
    }
}
