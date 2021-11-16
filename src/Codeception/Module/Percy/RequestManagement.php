<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exchange\ClientFactory;
use Codeception\Module\Percy\Exchange\Payload;

/**
 * Class RequestManagement
 *
 * @package Codeception\Module\Percy
 */
class RequestManagement
{
    /**
     * @var \Codeception\Module\Percy\ProcessManagement
     */
    private $processManagement;

    /**
     * @var \Codeception\Module\Percy\SnapshotManagement
     */
    private $snapshotManagement;

    /**
     * @var \Codeception\Module\Percy\Exchange\Payload[]
     */
    private $payloads = [];

    /**
     * RequestManagement constructor.
     *
     * @param \Codeception\Module\Percy\ProcessManagement  $processManagement
     * @param \Codeception\Module\Percy\SnapshotManagement $snapshotManagement
     */
    public function __construct(
        ProcessManagement $processManagement,
        SnapshotManagement $snapshotManagement
    ) {
        $this->processManagement = $processManagement;
        $this->snapshotManagement = $snapshotManagement;
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
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     */
    public function sendRequest(): void
    {
        if (!$this->hasPayloads()) {
            return;
        }

        $this->processManagement->startPercySnapshotServer();
        // TODO: Refactor client factory
        $client = ClientFactory::create();

        foreach ($this->payloads as $payload) {
            codecept_debug(sprintf('[Percy] Sending snapshot "%s"', $payload->getName()));

            $client->post(config('percy')->get('snapshotPath'), $payload);
        }

        $this->processManagement->stopPercySnapshotServer();

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
