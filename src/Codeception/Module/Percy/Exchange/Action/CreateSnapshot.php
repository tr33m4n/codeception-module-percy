<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Action;

use Codeception\Module\Percy\Exchange\Action\Request\Snapshot;
use Codeception\Module\Percy\Exchange\Action\Response\CreateSnapshot as CreateSnapshotResponse;
use Codeception\Module\Percy\Exchange\Client;

class CreateSnapshot
{
    /**
     * @var \Codeception\Module\Percy\Exchange\Client
     */
    private $client;

    /**
     * CreateSnapshot constructor.
     *
     * @param \Codeception\Module\Percy\Exchange\Client $client
     */
    public function __construct(
        Client $client
    ) {
        $this->client = $client;
    }

    /**
     * Create snapshot
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @param int                                                        $snapshotId
     * @param \Codeception\Module\Percy\Exchange\Action\Request\Snapshot $snapshot
     * @return \Codeception\Module\Percy\Exchange\Action\Response\CreateSnapshot
     */
    public function execute(int $snapshotId, Snapshot $snapshot): CreateSnapshotResponse
    {
        return CreateSnapshotResponse::create(
            $this->client->post(
                sprintf('builds/%s/snapshots', $snapshotId),
                [
                    'type' => 'snapshots',
                    'attributes' => $snapshot->asAttributesArray()
                ]
            )
        );
    }
}
