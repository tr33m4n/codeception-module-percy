<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\CiEnvironment\CiType;

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

    /**
     * Get nonce
     *
     * @return string|null
     */
    public function getNonce(): ?string;
}
