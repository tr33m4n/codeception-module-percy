<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType;

use Codeception\Module\Percy\Config\Environment\CiType;

class Semaphore implements CiTypeInterface
{
    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        return $_ENV['PULL_REQUEST_NUMBER'] ?? $_ENV['SEMAPHORE_GIT_PR_NUMBER'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getBranch(): ?string
    {
        return $_ENV['BRANCH_NAME'] ?? $_ENV['SEMAPHORE_GIT_PR_BRANCH'] ?? $_ENV['SEMAPHORE_GIT_BRANCH'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getCommit(): ?string
    {
        return $_ENV['REVISION'] ?? $_ENV['SEMAPHORE_GIT_PR_SHA'] ?? $_ENV['SEMAPHORE_GIT_SHA'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getInfo(): string
    {
        return isset($_ENV['SEMAPHORE_GIT_SHA'])
            ? sprintf('%s/2.0', (string) CiType::SEMAPHORE())
            : (string) CiType::SEMAPHORE();
    }

    /**
     * @inheritDoc
     */
    public function detect(): bool
    {
        return isset($_ENV['SEMAPHORE']) && $_ENV['SEMAPHORE'] === 'true';
    }
}
