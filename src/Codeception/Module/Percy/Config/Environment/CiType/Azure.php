<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType;

use Codeception\Module\Percy\Config\Environment\CiType;

class Azure implements CiTypeInterface
{
    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        return $_ENV['SYSTEM_PULLREQUEST_PULLREQUESTID'] ?? $_ENV['SYSTEM_PULLREQUEST_PULLREQUESTNUMBER'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getBranch(): ?string
    {
        return $_ENV['SYSTEM_PULLREQUEST_SOURCEBRANCH'] ?? $_ENV['BUILD_SOURCEBRANCHNAME'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getCommit(): ?string
    {
        return $_ENV['SYSTEM_PULLREQUEST_SOURCECOMMITID'] ?? $_ENV['BUILD_SOURCEVERSION'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getInfo(): string
    {
        return (string) CiType::AZURE();
    }

    /**
     * @inheritDoc
     */
    public function detect(): bool
    {
        return isset($_ENV['TF_BUILD']) && $_ENV['TF_BUILD'] === 'True';
    }
}
