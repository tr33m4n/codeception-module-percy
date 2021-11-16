<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exchange\Payload;
use GuzzleHttp\Client;

/**
 * Class RequestManagement
 *
 * @package Codeception\Module\Percy
 */
class RequestManagement
{
    /**
     * @var \Codeception\Module\Percy\SnapshotManagement
     */
    private $snapshotManagement;

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @var \Codeception\Module\Percy\Exchange\Payload[]
     */
    private $payloads = [];

    /**
     * RequestManagement constructor.
     *
     * @param \Codeception\Module\Percy\SnapshotManagement $snapshotManagement
     * @param \GuzzleHttp\Client                           $client
     */
    public function __construct(
        SnapshotManagement $snapshotManagement,
        Client $client
    ) {
        $this->snapshotManagement = $snapshotManagement;
        $this->client = $client;
    }

    /**
     * Add a payload
     *
     * @param \Codeception\Module\Percy\Exchange\Payload $payload
     * @return \Codeception\Module\Percy\RequestManagement
     */
    public function addPayload(Payload $payload): RequestManagement
    {
        $this->payloads[] = $payload;

        return $this;
    }

    /**
     * Check if request has payloads
     *
     * @return bool
     */
    public function hasPayloads(): bool
    {
        return $this->payloads !== [];
    }

    /**
     * Send payloads to Percy
     *
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendRequest(): void
    {
        if (!$this->hasPayloads()) {
            return;
        }

        foreach ($this->payloads as $payload) {
            codecept_debug(sprintf('[Percy] Sending snapshot "%s"', $payload->getName()));

            $this->client->post(config('percy')->get('snapshotPath'), (array) $payload);
        }

        $this->resetRequest();
    }

    /**
     * Reset payloads
     *
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     */
    public function resetRequest(): void
    {
        $this->payloads = [];

        if (config('percy')->get('cleanSnapshotStorage')) {
            $this->snapshotManagement->clean();
        }
    }
}
