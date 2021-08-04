<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment;

use Codeception\Module\Percy\Config\Environment\CiType\CiTypeInterface;

class CiEnvironment
{
    /**
     * @var \Codeception\Module\Percy\Config\Environment\CiType\CiTypeInterface
     */
    private $ciType;

    /**
     * CiEnvironment constructor.
     *
     * @param \Codeception\Module\Percy\Config\Environment\CiType\CiTypeInterface $ciType
     */
    public function __construct(
        CiTypeInterface $ciType
    ) {
        $this->ciType = $ciType;
    }

    /**
     * Get pull request
     *
     * @return string|null
     */
    public function getPullRequest() : ?string
    {
        return $this->ciType->getPullRequest();
    }

    /**
     * Get branch
     *
     * @return string|null
     */
    public function getBranch() : ?string
    {
        return preg_replace('/^refs\/\w+?\//', '', $this->ciType->getBranch() ?? '');
    }

    /**
     * Get commit
     *
     * @return string|null
     */
    public function getCommit() : ?string
    {
        return $this->ciType->getCommit();
    }

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo() : string
    {
        return $this->ciType->getInfo();
    }
}
