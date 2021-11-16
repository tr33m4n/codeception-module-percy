<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Action;

use Codeception\Module\Percy\Exchange\Action\Response\CreateBuild as CreateBuildResponse;
use Codeception\Module\Percy\Exchange\Client;
use tr33m4n\CodeceptionModulePercyEnvironment\EnvironmentProvider;

class CreateBuild
{
    /**
     * @var \Codeception\Module\Percy\Exchange\Client
     */
    private $client;

    /**
     * @var \tr33m4n\CodeceptionModulePercyEnvironment\EnvironmentProvider
     */
    private $environmentProvider;

    /**
     * CreateBuild constructor.
     *
     * @param \Codeception\Module\Percy\Exchange\Client                      $client
     * @param \tr33m4n\CodeceptionModulePercyEnvironment\EnvironmentProvider $environmentProvider
     */
    public function __construct(
        Client $client,
        EnvironmentProvider $environmentProvider
    ) {
        $this->client = $client;
        $this->environmentProvider = $environmentProvider;
    }

    /**
     * Create build action
     *
     * @throws \CzProject\GitPhp\GitException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \Codeception\Module\Percy\Exchange\Action\Response\CreateBuild
     */
    public function execute(): CreateBuildResponse
    {
        return CreateBuildResponse::create(
            $this->client->post(
                'build',
                [
                    'type' => 'builds',
                    'attributes' => [
                        'branch' => $this->environmentProvider->getGitEnvironment()->getBranch(),
                        'target-branch' => $this->environmentProvider->getPercyEnvironment()->getTargetBranch(),
                        'target-commit-sha' => $this->environmentProvider->getPercyEnvironment()->getTargetCommit(),
                        'commit-sha' => $this->environmentProvider->getGitEnvironment()->getSha(),
                        'commit-committed-at' => $this->environmentProvider->getGitEnvironment()->getCommittedAt(),
                        'commit-author-name' => $this->environmentProvider->getGitEnvironment()->getAuthorName(),
                        'commit-author-email' => $this->environmentProvider->getGitEnvironment()->getAuthorEmail(),
                        'commit-committer-name' => $this->environmentProvider->getGitEnvironment()->getCommitterName(),
                        'commit-committer-email' => $this->environmentProvider->getGitEnvironment()->getCommitterEmail(),
                        'commit-message' => $this->environmentProvider->getGitEnvironment()->getMessage(),
                        'pull-request-number' => $this->environmentProvider->getCiEnvironment()->getPullRequest(),
                        'parallel-nonce' => $this->environmentProvider->getPercyEnvironment()->getParallelNonce(),
                        'parallel-total-shards' => $this->environmentProvider->getPercyEnvironment()->getParallelTotal(),
                        'partial' => $this->environmentProvider->getPercyEnvironment()->getPartial()
                    ]
                ]
            )
        );
    }
}
