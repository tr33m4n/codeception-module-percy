<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType;

use Codeception\Module\Percy\Config\Environment\CiType;
use OndraM\CiDetector\Ci\Drone as CiDetectorDrone;

class Drone extends CiDetectorDrone implements CiTypeInterface
{
    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        return $_ENV['DRONE_PULL_REQUEST'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getSlug(): string
    {
        return (string) CiType::DRONE();
    }
}
