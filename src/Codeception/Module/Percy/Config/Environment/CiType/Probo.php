<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType;

use Codeception\Module\Percy\Config\Environment\CiType;

class Probo implements CiTypeInterface
{
    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        if (!isset($_ENV['PULL_REQUEST_LINK'])) {
            return null;
        }

        $ciPullRequestsParts = explode('/', $_ENV['PULL_REQUEST_LINK']);
        return end($ciPullRequestsParts);
    }

    /**
     * @inheritDoc
     */
    public function getBranch(): ?string
    {
        return $_ENV['BRANCH_NAME'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getCommit(): ?string
    {
        return $_ENV['COMMIT_REF'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getInfo(): string
    {
        return (string) CiType::PROBO();
    }

    /**
     * @inheritDoc
     */
    public function detect(): bool
    {
        return isset($_ENV['PROBO_ENVIRONMENT']) && $_ENV['PROBO_ENVIRONMENT'] === 'TRUE';
    }
}
