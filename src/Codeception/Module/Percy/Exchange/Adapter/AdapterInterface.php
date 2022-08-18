<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Adapter;

interface AdapterInterface
{
    /**
     * Set adapter URI
     */
    public function setUri(string $uri): AdapterInterface;

    /**
     * Set payload
     */
    public function setPayload(string $payload): AdapterInterface;

    /**
     * Set headers
     *
     * @param string[] $headers
     */
    public function setHeaders(array $headers): AdapterInterface;

    /**
     * Execute
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     */
    public function execute(): string;
}
