<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\PercyEnvironment;

use Codeception\Module\Percy\Config\CiEnvironment\CiEnvironment;

class PercyEnvironment
{
    /**
     * @var \Codeception\Module\Percy\Config\CiEnvironment\CiEnvironment
     */
    private $ciEnvironment;

    /**
     * PercyEnvironment constructor.
     *
     * @param \Codeception\Module\Percy\Config\CiEnvironment\CiEnvironment $ciEnvironment
     */
    public function __construct(
        CiEnvironment $ciEnvironment
    ) {
        $this->ciEnvironment = $ciEnvironment;
    }

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

    /**
     * Get parallel nonce
     *
     * @return string|null
     */
    public function getParallelNonce(): ?string
    {
        return $_ENV['PERCY_PARALLEL_NONCE'] ?? $this->ciEnvironment->getNonce();
    }

    /**
     * Get parallel total
     *
     * @return int
     */
    public function getParallelTotal(): int
    {
        if (isset($_ENV['PERCY_PARALLEL_TOTAL']) && is_numeric($_ENV['PERCY_PARALLEL_TOTAL'])) {
            return (int) $_ENV['PERCY_PARALLEL_TOTAL'];
        }

        return 0;
    }
}
