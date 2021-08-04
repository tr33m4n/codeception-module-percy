<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType;

use Codeception\Module\Percy\Config\Environment\CiType;

class CodeShip implements CiTypeInterface
{
    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        return $_ENV['CI_PR_NUMBER'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getBranch(): ?string
    {
        return $_ENV['CI_BRANCH'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getCommit(): ?string
    {
        return $_ENV['CI_COMMIT_ID'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getInfo(): string
    {
        return (string) CiType::CODESHIP();
    }

    /**
     * @inheritDoc
     */
    public function detect(): bool
    {
        return isset($_ENV['CI_NAME']) && $_ENV['CI_NAME'] === (string) CiType::CODESHIP();
    }
}
