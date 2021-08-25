<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\PercyEnvironment;

class PercyEnvironment
{
    /**
     * Get pull request
     *
     * @return string|null
     */
    public function getPullRequest(): ?string
    {
        return $_ENV['PERCY_PULL_REQUEST'] ?? null;
    }

    /**
     * Get branch
     *
     * @return string|null
     */
    public function getBranch(): ?string
    {
        return $_ENV['PERCY_BRANCH'] ?? null;
    }

    /**
     * Get commit
     *
     * @return string|null
     */
    public function getCommit(): ?string
    {
        return $_ENV['PERCY_COMMIT'] ?? null;
    }

    /**
     * Get target commit
     *
     * @return string|null
     */
    public function getTargetCommit(): ?string
    {
        return $_ENV['PERCY_TARGET_COMMIT'] ?? null;
    }

    /**
     * Get target branch
     *
     * @return string|null
     */
    public function getTargetBranch(): ?string
    {
        return $_ENV['PERCY_TARGET_BRANCH'] ?? null;
    }
}
