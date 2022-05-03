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

    /**
     * SnapshotManagement constructor.
     *
     * @param \Codeception\Module\Percy\ConfigManagement         $configManagement
     * @param \Codeception\Module\Percy\SnapshotRepository       $snapshotRepository
     * @param \Codeception\Module\Percy\ProcessManagement        $processManagement
     * @param \Codeception\Module\Percy\Exchange\ClientInterface $client
     */
    public function __construct(
        ConfigManagement $configManagement,
        SnapshotRepository $snapshotRepository,
        ProcessManagement $processManagement,
        ClientInterface $client
    ) {
        $this->configManagement = $configManagement;
        $this->snapshotRepository = $snapshotRepository;
        $this->processManagement = $processManagement;
        $this->client = $client;
    }

    /**
     * Create snapshot
     *
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @throws \JsonException
     * @param string               $domString
     * @param string               $name
     * @param string               $currentUrl
     * @param string               $clientInfo
     * @param string               $environmentInfo
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
        // Passing `*` will load all snapshots from all runs, not just the current one
        $snapshots = $this->snapshotRepository->loadAll('*');
        if ([] === $snapshots) {
            $this->debug('No snapshots to send!');

            return;
        }

        $this->debug(sprintf('Sending %s Percy snapshots...', count($snapshots)));

        $this->processManagement->startPercySnapshotServer();

        foreach ($snapshots as $snapshot) {
            $this->debug(sprintf('Sending snapshot "%s"', $snapshot->getName()));

            $this->client->post($this->configManagement->getSnapshotServerUri(), $snapshot);
        }

        $this->processManagement->stopPercySnapshotServer();

        $this->debug('All snapshots sent!');
    }

    /**
     * Reset
     */
    public function reset(): void
    {
        $this->snapshotRepository->deleteAll();
    }

    /**
     * Output debug message
     *
     * @param string $message
     */
    private function debug(string $message): void
    {
        codecept_debug(sprintf('[%s] %s', Definitions::NAMESPACE, $message));
    }
}
