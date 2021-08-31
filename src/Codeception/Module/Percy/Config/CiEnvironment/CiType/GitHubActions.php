<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\CiEnvironment\CiType;

use Codeception\Module\Percy\Config\CiEnvironment\CiType;
use Codeception\Module\Percy\Config\CiEnvironment\CiType\GitHub\EventDataProvider;
use OndraM\CiDetector\Ci\GitHubActions as CiDetectorGitHubActions;
use OndraM\CiDetector\Env;

class GitHubActions extends CiDetectorGitHubActions implements CiTypeInterface
{
    /**
     * @var \Codeception\Module\Percy\Config\CiEnvironment\CiType\GitHub\EventDataProvider
     */
    private $eventDataProvider;

    /**
     * GitHub constructor.
     *
     * @param \Codeception\Module\Percy\Config\CiEnvironment\CiType\GitHub\EventDataProvider $eventDataProvider
     * @param \OndraM\CiDetector\Env                                                         $env
     */
    public function __construct(
        EventDataProvider $eventDataProvider,
        Env $env
    ) {
        $this->eventDataProvider = $eventDataProvider;

        parent::__construct($env);
    }

    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        return $this->eventDataProvider->get('pull_request.number');
    }

    /**
     * @inheritDoc
     */
    public function getSlug(): string
    {
        return isset($_ENV['PERCY_GITHUB_ACTION'])
            ? sprintf('%s/%s', (string) CiType::GITHUB_ACTIONS(), $_ENV['PERCY_GITHUB_ACTION'] ?? '')
            : (string) CiType::GITHUB_ACTIONS();
    }

    /**
     * @inheritDoc
     */
    public function getNonce(): ?string
    {
        return $_ENV['GITHUB_RUN_ID'] ?? null;
    }
}
