<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\ConfigManagement;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\UriInterface;

class UriFactory
{
    private ConfigManagement $configManagement;

    /**
     * UriFactory constructor.
     */
    public function __construct(
        ConfigManagement $configManagement
    ) {
        $this->configManagement = $configManagement;
    }

    /**
     * Create base URI
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     */
    public function createBaseUri(): UriInterface
    {
        return Utils::uriFor('')
            ->withScheme('http')
            ->withHost('localhost')
            ->withPort($this->configManagement->getSnapshotServerPort());
    }

    /**
     * Create snapshot URI
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     */
    public function createSnapshotUri(): UriInterface
    {
        return $this->createBaseUri()->withPath('/percy/snapshot');
    }

    /**
     * Create health check URI
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     */
    public function createHealthCheckUri(): UriInterface
    {
        return $this->createBaseUri()->withPath('/percy/healthcheck');
    }
}
