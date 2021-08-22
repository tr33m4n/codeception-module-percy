<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\CiEnvironment\CiType;

use Codeception\Module\Percy\Config\CiEnvironment\CiType;
use OndraM\CiDetector\Ci\Travis as CiDetectorTravis;

class Travis extends CiDetectorTravis implements CiTypeInterface
{
    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        return isset($_ENV['TRAVIS_PULL_REQUEST']) && $_ENV['TRAVIS_PULL_REQUEST'] !== 'false'
            ? $_ENV['TRAVIS_PULL_REQUEST']
            : null;
    }

    /**
     * @inheritDoc
     */
    public function getSlug(): string
    {
        return (string) CiType::TRAVIS();
    }
}
