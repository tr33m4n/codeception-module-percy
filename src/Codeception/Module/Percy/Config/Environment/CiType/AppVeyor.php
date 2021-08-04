<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType;

use Codeception\Module\Percy\Config\Environment\CiType;

class AppVeyor implements CiTypeInterface
{
    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        return $_ENV['APPVEYOR_PULL_REQUEST_NUMBER'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getBranch(): ?string
    {
        return $_ENV['APPVEYOR_PULL_REQUEST_HEAD_REPO_BRANCH'] ?? $_ENV['APPVEYOR_REPO_BRANCH'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getCommit(): ?string
    {
        return $_ENV['APPVEYOR_PULL_REQUEST_HEAD_COMMIT'] ?? $_ENV['APPVEYOR_REPO_COMMIT'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getInfo(): string
    {
        return (string) CiType::APPVEYOR();
    }

    /**
     * @inheritDoc
     */
    public function detect(): bool
    {
        return isset($_ENV['APPVEYOR']) && ($_ENV['APPVEYOR'] === 'True' || $_ENV['APPVEYOR'] === 'true');
    }
}
