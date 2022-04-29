<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exchange\ClientInterface;
use Codeception\Module\Percy\Exchange\Payload;

class RequestManagement
{
    private ConfigManagement $configManagement;

    private CleanSnapshots $cleanSnapshots;

    private ProcessManagement $processManagement;

    private ClientInterface $client;

    /**
     * @var \Codeception\Module\Percy\Exchange\Payload[]
     */
    private array $payloads = [];

    /**
     * RequestManagement constructor.
     *
     * @param \Codeception\Module\Percy\ConfigManagement         $configManagement
     * @param \Codeception\Module\Percy\CleanSnapshots           $cleanSnapshots
     * @param \Codeception\Module\Percy\ProcessManagement        $processManagement
     * @param \Codeception\Module\Percy\Exchange\ClientInterface $client
     */
    public function __construct(
        ConfigManagement $configManagement,
        CleanSnapshots $cleanSnapshots,
        ProcessManagement $processManagement,
        ClientInterface $client
    ) {
        $this->configManagement = $configManagement;
        $this->cleanSnapshots = $cleanSnapshots;
        $this->processManagement = $processManagement;
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
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     */
    public function sendRequest(): void
    {
        if (!$this->hasPayloads()) {
            return;
        }

        $this->processManagement->startPercySnapshotServer();

        foreach ($this->payloads as $payload) {
            codecept_debug(sprintf('[Percy] Sending snapshot "%s"', $payload->getName()));

            $this->client->post($this->configManagement->getSnapshotPath(), $payload);
        }

        $this->processManagement->stopPercySnapshotServer();

        $this->resetRequest();
    }

    /**
     * Reset payloads
     */
    public function resetRequest(): void
    {
        $this->payloads = [];
        $this->cleanSnapshots->execute();
    }
}
