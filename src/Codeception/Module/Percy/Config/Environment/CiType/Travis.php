<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType;

use Codeception\Module\Percy\Config\Environment\CiType;

class Travis implements CiTypeInterface
{
    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        return isset($_ENV['TRAVIS_PULL_REQUEST']) && $_ENV['TRAVIS_PULL_REQUEST'] !== 'false'
            ? $_ENV['TRAVIS_PULL_REQUEST']
            : null;
    }

    /**
     * @inheritDoc
     */
    public function getBranch(): ?string
    {
        return null !== $this->getPullRequest()
            ? $_ENV['TRAVIS_PULL_REQUEST_BRANCH'] ?? null
            : $_ENV['TRAVIS_BRANCH'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getCommit(): ?string
    {
        return $_ENV['TRAVIS_COMMIT'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getInfo(): string
    {
        return (string) CiType::TRAVIS();
    }

    /**
     * @inheritDoc
     */
    public function detect(): bool
    {
        return isset($_ENV['TRAVIS_BUILD_ID']);
    }
}
