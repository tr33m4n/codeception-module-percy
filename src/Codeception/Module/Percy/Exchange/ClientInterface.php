<?php

namespace Codeception\Module\Percy\Exchange;

/**
 * Interface ClientInterface
 *
 * @package Codeception\Module\Percy\Exchange
 */
interface ClientInterface
{
    /**
     * From URL
     *
     * @param string $url
     * @return \Codeception\Module\Percy\Exchange\ClientInterface
     */
    public static function fromUrl(string $url) : ClientInterface;

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
     * @throws \Codeception\Module\Percy\Exception\ClientException
     * @return string
     */
    public function get() : string;

    /**
     * Post
     *
     * @throws \Codeception\Module\Percy\Exception\ClientException
     * @return string
     */
    public function post() : string;
}
