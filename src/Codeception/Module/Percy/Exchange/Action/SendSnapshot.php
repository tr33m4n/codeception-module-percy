<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Action;

use Codeception\Module\Percy\Exchange\Action\Request\Snapshot;
use Codeception\Module\Percy\Exchange\Action\Response\CreateSnapshot as CreateSnapshotResponse;

class SendSnapshot
{
    /**
     * @var \Codeception\Module\Percy\Exchange\Action\CreateSnapshot
     */
    private $createSnapshot;

    /**
     * @var \Codeception\Module\Percy\Exchange\Action\FinalizeSnapshot
     */
    private $finalizeSnapshot;

    /**
     * SendSnapshot constructor.
     *
     * @param \Codeception\Module\Percy\Exchange\Action\CreateSnapshot   $createSnapshot
     * @param \Codeception\Module\Percy\Exchange\Action\FinalizeSnapshot $finalizeSnapshot
     */
    public function __construct(
        CreateSnapshot $createSnapshot,
        FinalizeSnapshot $finalizeSnapshot
    ) {
        $this->createSnapshot = $createSnapshot;
        $this->finalizeSnapshot = $finalizeSnapshot;
    }

    /**
     * Send snapshot
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @param int                                                        $buildId
     * @param \Codeception\Module\Percy\Exchange\Action\Request\Snapshot $snapshot
     * @return \Codeception\Module\Percy\Exchange\Action\Response\CreateSnapshot
     */
    public function execute(int $buildId, Snapshot $snapshot): CreateSnapshotResponse
    {
        $snapshotResponse = $this->createSnapshot->execute($buildId, $snapshot);

        // TODO: Handle missing resources

        $this->finalizeSnapshot->execute($snapshotResponse->getId());

        return $snapshotResponse;
    }
}
