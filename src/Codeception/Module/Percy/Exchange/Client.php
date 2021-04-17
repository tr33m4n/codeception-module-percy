<?php
declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\Exchange\Adapter\AdapterInterface;

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
     * @var Payload|null
     */
    private $payload;

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
    public static function create(AdapterInterface $adapter) : ClientInterface
    {
        return new self($adapter);
    }

    /**
     * @inheritDoc
     */
    public function setPayload(Payload $payload) : ClientInterface
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(string $path) : string
    {
        return $this->adapter->setPath($path)->execute();
    }

    /**
     * @inheritDoc
     */
    public function post(string $path) : string
    {
        $payloadAsString = (string) $this->payload;
        $this->payload = null;

        return $this->adapter->setPath($path)
            ->setIsPost()
            ->setPayload($payloadAsString)
            ->setHeaders([
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payloadAsString)
            ])->execute();
    }
}
