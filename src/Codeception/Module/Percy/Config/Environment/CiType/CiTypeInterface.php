<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType;

use OndraM\CiDetector\Ci\CiInterface;

interface CiTypeInterface extends CiInterface
{
    /**
     * Get pull request
     *
     * @return string|null
     */
    public function getPullRequest(): ?string;

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug(): string;
}
