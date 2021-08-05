<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType;

use Codeception\Module\Percy\Config\Environment\CiType;
use OndraM\CiDetector\Ci\Circle as CiDetectorCircle;

class Circle extends CiDetectorCircle implements CiTypeInterface
{
    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        if (!isset($_ENV['CIRCLE_PULL_REQUEST'])) {
            return null;
        }

        $ciPullRequestsParts = explode('/', $_ENV['CIRCLE_PULL_REQUEST']);
        return end($ciPullRequestsParts);
    }

    /**
     * @inheritDoc
     */
    public function getSlug(): string
    {
        return (string) CiType::CIRCLE();
    }
}
