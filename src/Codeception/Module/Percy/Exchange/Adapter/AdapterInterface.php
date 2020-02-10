<?php

namespace Codeception\Module\Percy\Exchange\Adapter;

/**
 * Interface AdapterInterface
 *
 * @package Codeception\Module\Percy\Exchange
 */
interface AdapterInterface
{
    /**
     * Set adapter base URL
     *
     * @param string $baseUrl
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setBaseUrl(string $baseUrl) : AdapterInterface;

    /**
     * Set adapter path
     *
     * @param string $path
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setPath(string $path) : AdapterInterface;

    /**
     * Set is POST
     *
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setIsPost() : AdapterInterface;

    /**
     * Set payload
     *
     * @param string $payload
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setPayload(string $payload) : AdapterInterface;

    /**
     * Set headers
     *
     * @param array $headers
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setHeaders(array $headers) : AdapterInterface;

    /**
     * Execute
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @return string
     */
    public function execute() : string;
}
