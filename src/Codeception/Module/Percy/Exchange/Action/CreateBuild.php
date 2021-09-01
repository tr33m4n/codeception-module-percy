<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Action;

use Codeception\Module\Percy\Config\Provider;
use Codeception\Module\Percy\Exchange\Resource;
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
     * @param \Codeception\Module\Percy\Exchange\Resource[] $resources
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function execute(array $resources = [])
    {
        $request = $this->client->request(
            'POST',
            'build',
            [
                'headers' => [
                    'Content-Type' => 'application/vnd.api+json',
                    'User-Agent' => $this->configProvider->getUserAgent()
                ],
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
                    'partial' => $this->configProvider->getPercyEnvironment()->getPartial()
                ],
                'relationships' => [
                    'resources' => [
                        'data' => array_map(function (Resource $resource) {
                            return [
                                'type' => 'resources',
                                'id' => $resource->getSha() ?? hash('sha256', $resource->getContent() ?? ''),
                                'attributes' => [
                                    'resource-url' => $resource->getUrl(),
                                    'is-root' => $resource->getRoot(),
                                    'mimetype' => $resource->getMimeType()
                                ]
                            ];
                        }, $resources)
                    ]
                ]
            ]
        );
    }
}
