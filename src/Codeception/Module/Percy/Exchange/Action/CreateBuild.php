<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Action;

use tr33m4n\CodeceptionModulePercyEnvironment\EnvironmentProvider;
use Codeception\Module\Percy\Exchange\Action\Response\CreateBuild as CreateBuildResponse;
use GuzzleHttp\ClientInterface;

class CreateBuild extends AbstractAction
{
    /**
     * @var \tr33m4n\CodeceptionModulePercyEnvironment\EnvironmentProvider
     */
    private $environmentProvider;

    /**
     * CreateBuild constructor.
     *
     * @param \GuzzleHttp\ClientInterface                                    $client
     * @param \tr33m4n\CodeceptionModulePercyEnvironment\EnvironmentProvider $environmentProvider
     */
    public function __construct(
        ClientInterface $client,
        EnvironmentProvider $environmentProvider
    ) {
        $this->environmentProvider = $environmentProvider;

        parent::__construct($client);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \CzProject\GitPhp\GitException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \Codeception\Module\Percy\Exchange\Action\Response\CreateBuild
     */
    public function execute(): CreateBuildResponse
    {
        return CreateBuildResponse::create(
            $this->post(
                'build',
                [
                    'headers' => [
                        'Content-Type' => 'application/vnd.api+json',
                        'User-Agent' => $this->environmentProvider->getUserAgent()
                    ],
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
