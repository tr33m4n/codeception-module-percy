<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\Exchange\Adapter\AdapterInterface;

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
     * @inheritDoc
     */
    public function post(string $path, Payload $payload = null): string
    {
        $payloadAsString = (string) $payload;

        return $this->adapter->setPath($path)
            ->setPayload($payloadAsString)
            ->setHeaders([
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payloadAsString)
            ])->execute();
    }
}
