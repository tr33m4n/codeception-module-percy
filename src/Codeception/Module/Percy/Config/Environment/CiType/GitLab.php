<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType;

use Codeception\Module\Percy\Config\Environment\CiType;

class GitLab implements CiTypeInterface
{
    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        return $_ENV['CI_MERGE_REQUEST_IID'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getBranch(): ?string
    {
        return $_ENV['CI_COMMIT_REF_NAME'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getCommit(): ?string
    {
        return $_ENV['CI_COMMIT_SHA'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getInfo(): string
    {
        return sprintf('%s/%s', (string) CiType::GITLAB(), $_ENV['CI_SERVER_VERSION'] ?? '');
    }

    /**
     * @inheritDoc
     */
    public function detect(): bool
    {
        return isset($_ENV['GITLAB_CI']) && $_ENV['GITLAB_CI'] === 'true';
    }
}
