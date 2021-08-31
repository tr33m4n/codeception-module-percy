<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\CiEnvironment\CiType;

use Codeception\Module\Percy\Config\CiEnvironment\CiType;
use OndraM\CiDetector\Ci\Codeship as CiDetectorCodeship;

class CodeShip extends CiDetectorCodeship implements CiTypeInterface
{
    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        return $_ENV['CI_PR_NUMBER'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getSlug(): string
    {
        return (string) CiType::CODESHIP();
    }

    /**
     * @inheritDoc
     */
    public function getNonce(): ?string
    {
        return $_ENV['CI_BUILD_NUMBER'] ?? $_ENV['CI_BUILD_ID'] ?? null;
    }
}
