<?php

namespace Codeception\Module\Percy\Exchange\Adapter;

use Codeception\Module\Percy\Exception\AdapterException;

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
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @param string $baseUrl
     */
    public function __construct(string $baseUrl)
    {
        $this->resource = curl_init();

        $this->setBaseUrl($baseUrl);
        $this->setDefaults();
    }

    /**
     * Create new instance
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @param string $baseUrl
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public static function create(string $baseUrl) : AdapterInterface
    {
        return new self($baseUrl);
    }

    /**
     * @inheritDoc
     */
    public function setBaseUrl(string $baseUrl) : AdapterInterface
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @param string $path
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setPath(string $path) : AdapterInterface
    {
        curl_setopt($this->getResource(), CURLOPT_URL, rtrim($this->baseUrl, '/') . '/' . $path);

        return $this;
    }

    /**
     * Set defaults
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setDefaults() : AdapterInterface
    {
        curl_setopt_array($this->getResource(), [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true
        ]);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setIsPost() : AdapterInterface
    {
        curl_setopt($this->getResource(), CURLOPT_POST, true);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @param string $payload
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setPayload(string $payload) : AdapterInterface
    {
        curl_setopt($this->getResource(), CURLOPT_POSTFIELDS, $payload);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @param string[] $headers
     * @return \Codeception\Module\Percy\Exchange\Adapter\AdapterInterface
     */
    public function setHeaders(array $headers) : AdapterInterface
    {
        curl_setopt($this->getResource(), CURLOPT_HTTPHEADER, $headers);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function execute() : string
    {
        $output = curl_exec($this->getResource());
        if (curl_errno($this->getResource()) !== 0) {
            throw new AdapterException(curl_error($this->getResource()));
        }

        // Restore default state after executing
        curl_reset($this->getResource());
        $this->setDefaults();

        return is_string($output) ? $output : '';
    }

    /**
     * Get resource
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @return resource
     */
    private function getResource()
    {
        if (!$this->resource) {
            throw new AdapterException('Resource has not been initialised');
        }

        return $this->resource;
    }
}
