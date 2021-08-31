<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\CiEnvironment\CiType;

use Codeception\Module\Percy\Config\CiEnvironment\CiType;
use OndraM\CiDetector\Ci\SourceHut as CiDetectorSourceHut;

class SourceHut extends CiDetectorSourceHut implements CiTypeInterface
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
    public function getSlug(): string
    {
        return (string) CiType::SOURCEHUT();
    }

    /**
     * @inheritDoc
     */
    public function getNonce(): ?string
    {
        return $_ENV['JOB_ID'] ?? null;
    }
}
