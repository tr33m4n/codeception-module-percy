<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType;

use Codeception\Module\Percy\Config\Environment\CiType;

class Unknown implements CiTypeInterface
{
    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getBranch(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getCommit(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getInfo(): string
    {
        return (string) CiType::UNKNOWN();
    }

    /**
     * @inheritDoc
     */
    public function detect(): bool
    {
        return true;
    }
}
