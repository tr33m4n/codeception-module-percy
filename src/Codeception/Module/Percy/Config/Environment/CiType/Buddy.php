<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType;

use Codeception\Module\Percy\Config\Environment\CiType;
use OndraM\CiDetector\Ci\Buddy as CiDetectorBuddy;

class Buddy extends CiDetectorBuddy implements CiTypeInterface
{
    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        return $_ENV['BUDDY_EXECUTION_PULL_REQUEST_ID'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getSlug(): string
    {
        return (string) CiType::BUDDY();
    }
}
