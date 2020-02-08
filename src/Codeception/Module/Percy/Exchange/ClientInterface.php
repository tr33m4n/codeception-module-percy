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
     * @return static
     */
    public static function fromUrl(string $url) : self;

    /**
     * With payload
     *
     * @param \Codeception\Module\Percy\Exchange\Payload $payload
     * @return $this
     */
    public function withPayload(Payload $payload) : self;

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
