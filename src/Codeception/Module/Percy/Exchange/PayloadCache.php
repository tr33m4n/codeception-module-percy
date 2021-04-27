<?php
declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

/**
 * Class PayloadCache
 *
 * @package Codeception\Module\Percy\Exchange
 */
class PayloadCache
{
    /**
     * @var \Codeception\Module\Percy\Exchange\Payload[]
     */
    private $payloads = [];

    /**
     * Create cache
     *
     * @return \Codeception\Module\Percy\Exchange\PayloadCache
     */
    public static function create() : PayloadCache
    {
        return new self;
    }

    /**
     * Add a payload to the cache
     *
     * @param \Codeception\Module\Percy\Exchange\Payload $payload
     * @return $this
     */
    public function add(Payload $payload) : PayloadCache
    {
        $this->payloads[] = $payload;

        return $this;
    }

    /**
     * Get all payloads
     *
     * @return \Codeception\Module\Percy\Exchange\Payload[]
     */
    public function all() : array
    {
        return $this->payloads;
    }

    /**
     * Clear payload cache
     */
    public function clear() : void
    {
        $this->payloads = [];

        SnapshotStorage::clean();
    }
}
