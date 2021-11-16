<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Action;

use Codeception\Module\Percy\Exchange\Action\Response\FinalizeBuild as FinalizeBuildResponse;
use Codeception\Module\Percy\Exchange\Client;

class FinalizeBuild
{
    /**
     * @var \Codeception\Module\Percy\Exchange\Client
     */
    private $client;

    /**
     * FinalizeBuild constructor.
     *
     * @param \Codeception\Module\Percy\Exchange\Client $client
     */
    public function __construct(
        Client $client
    ) {
        $this->client = $client;
    }

    /**
     * Finalize build
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @param int $buildId
     * @return \Codeception\Module\Percy\Exchange\Action\Response\FinalizeBuild
     */
    public function execute(int $buildId): FinalizeBuildResponse
    {
        return FinalizeBuildResponse::create($this->client->post(sprintf('builds/%s/finalize', $buildId)));
    }
}
