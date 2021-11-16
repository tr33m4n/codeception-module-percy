<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\Exchange\Action\Request\Snapshot;
use Codeception\Module\Percy\Exchange\Action\CreateBuild;
use Codeception\Module\Percy\Exchange\Action\SendSnapshot;
use Codeception\Module\Percy\Persistence\DomStorage;

class RequestManagement
{
    private $domStorage;

    private $createBuild;

    private $sendSnapshot;

    /**
     * @var \Codeception\Module\Percy\Exchange\Action\Request\Snapshot[]
     */
    private $snapshots = [];

    /**
     * RequestManagement constructor.
     *
     * @param \Codeception\Module\Percy\Persistence\DomStorage       $domStorage
     * @param \Codeception\Module\Percy\Exchange\Action\CreateBuild  $createBuild
     * @param \Codeception\Module\Percy\Exchange\Action\SendSnapshot $sendSnapshot
     */
    public function __construct(
        DomStorage $domStorage,
        CreateBuild $createBuild,
        SendSnapshot $sendSnapshot
    ) {
        $this->domStorage = $domStorage;
        $this->createBuild = $createBuild;
        $this->sendSnapshot = $sendSnapshot;
    }

    /**
     * Add a snapshot
     *
     * @param \Codeception\Module\Percy\Exchange\Action\Request\Snapshot $snapshot
     * @return \Codeception\Module\Percy\Exchange\RequestManagement
     */
    public function addSnapshot(Snapshot $snapshot): RequestManagement
    {
        $this->snapshots[] = $this->domStorage->save($snapshot);

        return $this;
    }

    /**
     * Check if request has snapshots
     *
     * @return bool
     */
    public function hasSnapshots(): bool
    {
        return $this->snapshots !== [];
    }

    /**
     * Send payloads to Percy
     *
     * @throws \CzProject\GitPhp\GitException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     */
    public function sendRequest(): void
    {
        if (!$this->hasSnapshots()) {
            return;
        }

        $createBuildResponse = $this->createBuild->execute();

        foreach ($this->snapshots as $snapshot) {
            //codecept_debug(sprintf('[Percy] Sending snapshot "%s"', $snapshot->getName()));

            $this->sendSnapshot->execute($createBuildResponse->getId(), $snapshot);
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
        $this->snapshots = [];

        if (config('percy')->get('cleanSnapshotStorage')) {
            $this->snapshotManagement->clean();
        }
    }
}
