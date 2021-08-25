<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Action;

use Codeception\Module\Percy\Config\Provider;
use GuzzleHttp\ClientInterface;

class CreateBuild
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $client;

    private $configProvider;

    public function __construct(
        ClientInterface $client,
        Provider $configProvider
    ) {
        $this->client = $client;
        $this->configProvider = $configProvider;
    }

    /**
     * Execute create build action
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function execute()
    {
        return $this->client->request(
            'POST',
            'build',
            [
                'type' => 'builds',
                'attributes' => [
                    'target-branch' => '',
                    'target-commit-sha' => '',
                    'commit-sha' => '',
                    'commit-committed-at' => '',
                    'commit-author-name' => '',
                    'commit-author-email' => '',
                    'commit-committer-name' => '',
                    'commit-committer-email' => '',
                    'commit-message' => '',
                    'pull-request-number' => '',
                    'parallel-nonce' => '',
                    'parallel-total-shards' => '',
                    'partial' => ''
                ],
                'relationships' => [
                    'resources' => []
                ]
            ]
        );
    }
}
