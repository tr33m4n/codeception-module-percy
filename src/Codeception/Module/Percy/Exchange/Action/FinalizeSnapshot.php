<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Action;

use Codeception\Module\Percy\Exchange\Action\Response\FinalizeSnapshot as FinalizeSnapshotResponse;
use Codeception\Module\Percy\Exchange\Client;

class FinalizeSnapshot
{
    /**
     * @var \Codeception\Module\Percy\Exchange\Client
     */
    private $client;

    /**
     * FinalizeSnapshot constructor.
     *
     * @param \Codeception\Module\Percy\Exchange\Client $client
     */
    public function __construct(
        Client $client
    ) {
        $this->client = $client;
    }

    /**
     * Finalize snapshot
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @param int $snapshotId
     * @return \Codeception\Module\Percy\Exchange\Action\Response\FinalizeSnapshot
     */
    public function execute(int $snapshotId): FinalizeSnapshotResponse
    {
        return FinalizeSnapshotResponse::create($this->client->post(sprintf('snapshots/%s/finalize', $snapshotId)));
    }
}
