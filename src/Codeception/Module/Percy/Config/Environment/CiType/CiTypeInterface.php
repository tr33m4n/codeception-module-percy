<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType;

interface CiTypeInterface
{
    /**
     * Get pull request
     *
     * @return string|null
     */
    public function getPullRequest(): ?string;

    /**
     * Get branch
     *
     * @return string|null
     */
    public function getBranch(): ?string;

    /**
     * Get commit
     *
     * @return string|null
     */
    public function getCommit(): ?string;

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo(): string;

    /**
     * Detect CI type
     *
     * @return bool
     */
    public function detect(): bool;
}
