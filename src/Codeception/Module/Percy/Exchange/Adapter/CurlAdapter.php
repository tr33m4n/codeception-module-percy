<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Adapter;

use Codeception\Module\Percy\Exception\AdapterException;

class CurlAdapter implements AdapterInterface
{
    /**
     * @var false|resource
     */
    private $resource;

    /**
     * CurlAdapter constructor.
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     */
    public function __construct()
    {
        $this->resource = curl_init();

        $this->setDefaults();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     */
    public function setUri(string $uri): AdapterInterface
    {
        curl_setopt($this->getResource(), CURLOPT_URL, $uri);

        return $this;
    }

    /**
     * Set defaults
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     */
    public function setDefaults(): AdapterInterface
    {
        curl_setopt_array($this->getResource(), [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_POST => true
        ]);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     */
    public function setPayload(string $payload): AdapterInterface
    {
        curl_setopt($this->getResource(), CURLOPT_POSTFIELDS, $payload);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @param string[] $headers
     */
    public function setHeaders(array $headers): AdapterInterface
    {
        curl_setopt($this->getResource(), CURLOPT_HTTPHEADER, $headers);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function execute(): string
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
