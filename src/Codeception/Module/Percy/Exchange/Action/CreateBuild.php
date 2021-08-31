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

    /**
     * @var \Codeception\Module\Percy\Config\Provider
     */
    private $configProvider;

    /**
     * CreateBuild constructor.
     *
     * @param \GuzzleHttp\ClientInterface               $client
     * @param \Codeception\Module\Percy\Config\Provider $configProvider
     */
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
     * @throws \ReflectionException
     * @throws \tr33m4n\Di\Exception\MissingClassException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @throws \tr33m4n\Utilities\Exception\ConfigException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function execute()
    {
        $request = $this->client->request(
            'POST',
            'build',
            [
                'type' => 'builds',
                'attributes' => [
                    'branch' => $this->configProvider->getGitEnvironment()->getBranch(),
                    'target-branch' => $this->configProvider->getPercyEnvironment()->getTargetBranch(),
                    'target-commit-sha' => $this->configProvider->getPercyEnvironment()->getTargetCommit(),
                    'commit-sha' => $this->configProvider->getGitEnvironment()->getSha(),
                    'commit-committed-at' => $this->configProvider->getGitEnvironment()->getCommittedAt(),
                    'commit-author-name' => $this->configProvider->getGitEnvironment()->getAuthorName(),
                    'commit-author-email' => $this->configProvider->getGitEnvironment()->getAuthorEmail(),
                    'commit-committer-name' => $this->configProvider->getGitEnvironment()->getCommitterName(),
                    'commit-committer-email' => $this->configProvider->getGitEnvironment()->getCommitterEmail(),
                    'commit-message' => $this->configProvider->getGitEnvironment()->getMessage(),
                    'pull-request-number' => $this->configProvider->getCiEnvironment()->getPullRequest(),
                    'parallel-nonce' => $this->configProvider->getPercyEnvironment()->getParallelNonce(),
                    'parallel-total-shards' => $this->configProvider->getPercyEnvironment()->getParallelTotal(),
                    'partial' => ''
                ],
                'relationships' => [
                    'resources' => []
                ]
            ]
        );
    }
}
