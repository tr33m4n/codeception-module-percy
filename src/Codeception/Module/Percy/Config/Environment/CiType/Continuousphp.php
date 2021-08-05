<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType;

use Codeception\Module\Percy\Config\Environment\CiType;
use OndraM\CiDetector\Ci\Continuousphp as CiDetectorContinuousphp;

class Continuousphp extends CiDetectorContinuousphp implements CiTypeInterface
{
    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        return $_ENV['CPHP_PR_ID'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getSlug(): string
    {
        return (string) CiType::CONTINUOUSPHP();
    }
}
