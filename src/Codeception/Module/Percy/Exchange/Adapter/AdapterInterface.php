<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Adapter;

interface AdapterInterface
{
    /**
     * Set adapter base URL
     *
     * @param string $baseUrl
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setBaseUrl(string $baseUrl): AdapterInterface;

    /**
     * Set adapter path
     *
     * @param string $path
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setPath(string $path): AdapterInterface;

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
