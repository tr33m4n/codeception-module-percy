<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\CiEnvironment\CiType;

use Codeception\Module\Percy\Config\CiEnvironment\CiType;
use OndraM\CiDetector\Ci\GitLab as CiDetectorGitLab;

class GitLab extends CiDetectorGitLab implements CiTypeInterface
{
    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        return $_ENV['CI_MERGE_REQUEST_IID'] ?? $_ENV['CI_EXTERNAL_PULL_REQUEST_IID'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getSlug(): string
    {
        return sprintf('%s/%s', (string) CiType::GITLAB(), $_ENV['CI_SERVER_VERSION'] ?? '');
    }

    /**
     * @inheritDoc
     */
    public function getNonce(): ?string
    {
        return $_ENV['CI_PIPELINE_ID'] ?? null;
    }
}