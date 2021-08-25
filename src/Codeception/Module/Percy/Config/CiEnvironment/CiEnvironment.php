<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\CiEnvironment;

use Codeception\Module\Percy\Config\PercyEnvironment\PercyEnvironment;

class CiEnvironment
{
    /**
     * @var \Codeception\Module\Percy\Config\CiEnvironment\CiType\CiTypeInterface
     */
    private $ciType;

    /**
     * @var \Codeception\Module\Percy\Config\PercyEnvironment\PercyEnvironment
     */
    private $percyEnvironment;

    /**
     * CiEnvironment constructor.
     *
     * @param \Codeception\Module\Percy\Config\CiEnvironment\CiTypeResolver      $ciTypeResolver
     * @param \Codeception\Module\Percy\Config\PercyEnvironment\PercyEnvironment $percyEnvironment
     */
    public function __construct(
        CiTypeResolver $ciTypeResolver,
        PercyEnvironment $percyEnvironment
    ) {
        $this->ciType = $ciTypeResolver->resolve();
        $this->percyEnvironment = $percyEnvironment;
    }

    /**
     * Get pull request
     *
     * @return string|null
     */
    public function getPullRequest(): ?string
    {
        return $this->percyEnvironment->getPullRequest() ?? $this->ciType->getPullRequest();
    }

    /**
     * Get branch
     *
     * @return string|null
     */
    public function getBranch(): ?string
    {
        return $this->percyEnvironment->getBranch() ?? $this->ciType->getBranch();
    }

    /**
     * Get commit
     *
     * @return string|null
     */
    public function getCommit(): ?string
    {
        return $this->percyEnvironment->getCommit() ?? $this->ciType->getCommit();
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->ciType->getSlug();
    }
}
