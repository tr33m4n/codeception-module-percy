<?php

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\Exchange\Adapter\AdapterInterface;

/**
 * Interface ClientInterface
 *
 * @package Codeception\Module\Percy\Exchange
 */
interface ClientInterface
{
    /**
     * From adapter
     *
     * @param \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface $adapter
     * @return \Codeception\Module\Percy\Exchange\ClientInterface
     * @author Daniel Doyle <dd@amp.co>
     */
    public static function fromAdapter(AdapterInterface $adapter) : ClientInterface;

    /**
     * With payload
     *
     * @param \Codeception\Module\Percy\Exchange\Payload $payload
     * @return \Codeception\Module\Percy\Exchange\ClientInterface
     */
    public function withPayload(Payload $payload) : ClientInterface;

    /**
     * Get
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @param string $path
     * @return string
     */
    public function get(string $path) : string;

    /**
     * Post
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @param string $path
     * @return string
     */
    public function post(string $path) : string;
}
