<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType;

use Codeception\Module\Percy\Config\Environment\CiType;
use OndraM\CiDetector\Ci\Jenkins as CiDetectorJenkins;

class Jenkins extends CiDetectorJenkins implements CiTypeInterface
{
    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        return $_ENV['CHANGE_ID'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getSlug(): string
    {
        return (string) CiType::JENKINS();
    }
}
