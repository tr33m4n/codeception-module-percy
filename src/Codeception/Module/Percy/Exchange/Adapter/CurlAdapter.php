<?php

namespace Codeception\Module\Percy\Exchange\Adapter;

use Codeception\Module\Percy\Exception\AdapterException;
use Codeception\Module\Percy;
use ReflectionClass;

/**
 * Class CurlAdapter
 *
 * @package Codeception\Module\Percy\Exchange\Adapter
 */
class CurlAdapter implements AdapterInterface
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var false|resource
     */
    private $resource;

    /**
     * CurlAdapter constructor.
     *
     * @param string $baseUrl
     */
    public function __construct(string $baseUrl)
    {
        $this->resource = curl_init();

        $this->setBaseUrl($baseUrl);
        $this->setDefaults();
    }

    /**
     * @inheritDoc
     *
     * @param string $baseUrl
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     * @author Daniel Doyle <dd@amp.co>
     */
    public function setBaseUrl(string $baseUrl) : AdapterInterface
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @param string $path
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setPath(string $path) : AdapterInterface
    {
        curl_setopt($this->resource, CURLOPT_URL, rtrim($this->baseUrl, '/') . '/' . $path);

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setDefaults() : AdapterInterface
    {
        curl_setopt_array($this->resource, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true
        ]);

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setIsPost() : AdapterInterface
    {
        curl_setopt($this->resource, CURLOPT_POST, true);

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @param string $payload
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setPayload(string $payload) : AdapterInterface
    {
        curl_setopt($this->resource, CURLOPT_POSTFIELDS, $payload);

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @param array $headers
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setHeaders(array $headers) : AdapterInterface
    {
        curl_setopt($this->resource, CURLOPT_HTTPHEADER, $headers);

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @throws \ReflectionException
     * @return string
     */
    public function execute() : string
    {
        $output = curl_exec($this->resource);
        if (curl_errno($this->resource)) {
            throw new AdapterException(
                (new ReflectionClass(Percy::class))->getShortName(),
                curl_error($this->resource)
            );
        }

        // Restore default state after executing
        curl_reset($this->resource);
        $this->setDefaults();

        return $output;
    }
}
