<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType;

use Codeception\Module\Percy\Config\Environment\CiType;

class Buildkite implements CiTypeInterface
{
    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        return isset($_ENV['BUILDKITE_PULL_REQUEST']) && $_ENV['BUILDKITE_PULL_REQUEST'] !== 'false'
            ? $_ENV['BUILDKITE_PULL_REQUEST']
            : null;
    }

    /**
     * @inheritDoc
     */
    public function getBranch(): ?string
    {
        return $_ENV['BUILDKITE_BRANCH'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getCommit(): ?string
    {
        return isset($_ENV['BUILDKITE_COMMIT']) && $_ENV['BUILDKITE_COMMIT'] !== 'HEAD'
            ? $_ENV['BUILDKITE_COMMIT']
            : null;
    }

    /**
     * @inheritDoc
     */
    public function getInfo(): string
    {
        return (string) CiType::BUILDKITE();
    }

    /**
     * @inheritDoc
     */
    public function detect(): bool
    {
        return isset($_ENV['BUILDKITE']) && $_ENV['BUILDKITE'] === 'true';
    }
}
