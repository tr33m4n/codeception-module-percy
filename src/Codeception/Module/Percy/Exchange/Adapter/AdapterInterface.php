<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Adapter;

interface AdapterInterface
{
    /**
     * Set adapter URI
     *
     * @param string $uri
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setUri(string $uri): AdapterInterface;

    /**
     * Set payload
     *
     * @param string $payload
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setPayload(string $payload): AdapterInterface;

    /**
     * Set headers
     *
     * @param string[] $headers
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setHeaders(array $headers): AdapterInterface;

    /**
     * Execute
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @return string
     */
    public function execute(): string;
}
