<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exchange\ClientInterface;

class SnapshotManagement
{
    private ConfigManagement $configManagement;

    private SnapshotRepository $snapshotRepository;

    private ProcessManagement $processManagement;

    private ClientInterface $client;

    private Debug $debug;

    /**
     * SnapshotManagement constructor.
     */
    public function __construct(
        ConfigManagement $configManagement,
        SnapshotRepository $snapshotRepository,
        ProcessManagement $processManagement,
        ClientInterface $client,
        Debug $debug
    ) {
        $this->configManagement = $configManagement;
        $this->snapshotRepository = $snapshotRepository;
        $this->processManagement = $processManagement;
        $this->client = $client;
        $this->debug = $debug;
    }

    /**
     * Create snapshot
     *
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @throws \JsonException
     * @param array<string, mixed> $additionalConfig
     */
    public function createSnapshot(
        string $domString,
        string $name,
        string $currentUrl,
        string $clientInfo,
        string $environmentInfo,
        array $additionalConfig = []
    ): void {
        $this->snapshotRepository->save(
            Snapshot::create(
                $domString,
                $name,
                $currentUrl,
                $clientInfo,
                $environmentInfo,
                $additionalConfig
            )
        );
    }

    /**
     * Send all snapshots
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @throws \JsonException
     */
    public function sendAll(): void
    {
        $this->sendInstance('*');
    }

    /**
     * Send instance snapshots
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @throws \JsonException
     * @param string|null $instanceId
     */
    public function sendInstance(string $instanceId = null): void
    {
        // Passing `*` will load all snapshots from all runs, not just the current one
        $snapshots = $this->snapshotRepository->loadAll($instanceId);
        if ([] === $snapshots) {
            $this->debug->out('No snapshots to send!');

            return;
        }

        $this->debug->out(sprintf('Sending %s Percy snapshots...', count($snapshots)));
        $this->processManagement->startPercySnapshotServer();

        foreach ($snapshots as $snapshot) {
            $this->debug->out(sprintf('Sending snapshot "%s"', $snapshot->getName()));
            $this->client->post($this->configManagement->getSnapshotServerUri(), $snapshot);
        }

        $this->processManagement->stopPercySnapshotServer();
        $this->debug->out('All snapshots sent!');
    }

    /**
     * Reset all
     */
    public function resetAll(): void
    {
        $this->resetInstance('*');
    }

    /**
     * Reset instance
     *
     * @param string|null $instanceId
     */
    public function resetInstance(string $instanceId = null): void
    {
        $this->snapshotRepository->deleteAll($instanceId);
    }
}
