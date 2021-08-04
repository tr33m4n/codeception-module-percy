<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType;

use Codeception\Module\Percy\Config\Environment\CiType;

class Jenkins implements CiTypeInterface
{
    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        return $_ENV['CHANGE_ID'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getBranch(): ?string
    {
        return $_ENV['CHANGE_BRANCH'] ?? $_ENV['GIT_BRANCH'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getCommit(): ?string
    {
        return 'TODO' ?? $_ENV['GIT_COMMIT'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getInfo(): string
    {
        return (string) CiType::JENKINS();
    }

    /**
     * @inheritDoc
     */
    public function detect(): bool
    {
        return isset($_ENV['JENKINS_URL']);
    }
}
