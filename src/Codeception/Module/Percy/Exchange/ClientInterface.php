<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\Payload;

/**
 * Interface ClientInterface
 *
 * @package Codeception\Module\Percy\Exchange
 */
interface ClientInterface
{
    /**
     * Get
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @param string $path
     * @return string
     */
    public function get(string $path): string;

    /**
     * Post
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @param string                                 $path
     * @param \Codeception\Module\Percy\Payload|null $payload
     * @return string
     */
    public function post(string $path, Payload $payload = null): string;
}
