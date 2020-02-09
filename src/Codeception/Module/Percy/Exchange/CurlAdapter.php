<?php

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\Exception\ClientException;

/**
 * Class CurlAdapter
 *
 * @package Codeception\Module\Percy\Exchange
 */
class CurlAdapter implements AdapterInterface
{
    /**
     * @var false|resource
     */
    private $resource;

    /**
     * CurlAdapter constructor.
     *
     * @param string|null $url
     */
    public function __construct(?string $url = null)
    {
        $this->resource = curl_init($url);
    }

    /**
     * @inheritDoc
     *
     * @param string $url
     * @return \Codeception\Module\Percy\Exchange\AdapterInterface
     */
    public function setUrl(string $url) : AdapterInterface
    {
        curl_setopt($this->resource, CURLOPT_URL, $url);

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @param array $options
     * @return \Codeception\Module\Percy\Exchange\AdapterInterface
     */
    public function setOptions(array $options) : AdapterInterface
    {
        curl_setopt_array($this->resource, $options);

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @throws \Codeception\Module\Percy\Exception\ClientException
     * @return string
     */
    public function execute() : string
    {
        $output = curl_exec($this->resource);
        if (curl_errno($this->resource)) {
            throw new ClientException(curl_error($this->resource));
        }

        curl_close($this->resource);

        return $output;
    }
}
