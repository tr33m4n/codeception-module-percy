<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment;

class CiEnvironment
{
    /**
     * @var \Codeception\Module\Percy\Config\Environment\CiType\CiTypeInterface
     */
    private $ciType;

    /**
     * CiEnvironment constructor.
     *
     * @param \Codeception\Module\Percy\Config\Environment\CiTypeResolver $ciTypeResolver
     */
    public function __construct(
        CiTypeResolver $ciTypeResolver
    ) {
        $this->ciType = $ciTypeResolver->resolve();
    }

    /**
     * Get pull request
     *
     * @return string|null
     */
    public function getPullRequest(): ?string
    {
        if (isset($_ENV['PERCY_PULL_REQUEST'])) {
            return $_ENV['PERCY_PULL_REQUEST'];
        }

        return $this->ciType->getPullRequest();
    }

    /**
     * Get branch
     *
     * @return string|null
     */
    public function getBranch(): ?string
    {
        if (isset($_ENV['PERCY_BRANCH'])) {
            return $_ENV['PERCY_BRANCH'];
        }

        return $this->ciType->getBranch();
    }

    /**
     * Get commit
     *
     * @return string|null
     */
    public function getCommit(): ?string
    {
        if (isset($_ENV['PERCY_COMMIT'])) {
            return $_ENV['PERCY_COMMIT'];
        }

        return $this->ciType->getCommit();
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
