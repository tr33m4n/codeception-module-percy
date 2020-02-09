<?php

namespace Codeception\Module\Percy\Exchange;

/**
 * Interface AdapterInterface
 *
 * @package Codeception\Module\Percy\Exchange
 */
interface AdapterInterface
{
    /**
     * Set adapter URL
     *
     * @param string $url
     * @return \Codeception\Module\Percy\Exchange\AdapterInterface
     */
    public function setUrl(string $url) : AdapterInterface;

    /**
     * Set adapter options
     *
     * @param array $options
     * @return \Codeception\Module\Percy\Exchange\AdapterInterface
     */
    public function setOptions(array $options) : AdapterInterface;

    /**
     * Execute
     *
     * @throws \Codeception\Module\Percy\Exception\ClientException
     * @return string
     */
    public function execute() : string;
}
