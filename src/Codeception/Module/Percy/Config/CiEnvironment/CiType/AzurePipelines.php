<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\CiEnvironment\CiType;

use Codeception\Module\Percy\Config\CiEnvironment\CiType;
use OndraM\CiDetector\Ci\AzurePipelines as CiDetectorAzurePipelines;

class AzurePipelines extends CiDetectorAzurePipelines implements CiTypeInterface
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
    public function getSlug(): string
    {
        return (string) CiType::AZURE_PIPELINES();
    }

    /**
     * @inheritDoc
     */
    public function getNonce(): ?string
    {
        return $_ENV['SYSTEM_JOBID'] ?? null;
    }
}