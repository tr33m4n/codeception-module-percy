<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\Snapshot;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;

class Client implements ClientInterface
{
    private GuzzleClient $guzzleClient;

    private UriFactory $uriFactory;

    /**
     * Client constructor.
     */
    public function __construct(
        GuzzleClient $guzzleClient,
        UriFactory $uriFactory
    ) {
        $this->guzzleClient = $guzzleClient;
        $this->uriFactory = $uriFactory;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     */
    public function sendSnapshot(Snapshot $snapshot): void
    {
        $this->guzzleClient->post(
            $this->uriFactory->createSnapshotUri(),
            [RequestOptions::JSON => $snapshot->toArray()]
        );
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     */
    public function performHealthCheck(): void
    {
        $this->guzzleClient->get($this->uriFactory->createHealthCheckUri());
    }
}
