<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\CiEnvironment\CiType;

use Codeception\Module\Percy\Config\CiEnvironment\CiType;
use OndraM\CiDetector\Env;
use OndraM\CiDetector\TrinaryLogic;

class Unknown implements CiTypeInterface
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
        return (string) CiType::UNKNOWN();
    }

    /**
     * @inheritDoc
     */
    public static function isDetected(Env $env): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getCiName(): string
    {
        return 'CI/Unknown';
    }

    /**
     * @inheritDoc
     */
    public function describe(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getBuildNumber(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getBuildUrl(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getCommit(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getBranch(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getTargetBranch(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getRepositoryName(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getRepositoryUrl(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function isPullRequest(): TrinaryLogic
    {
        return TrinaryLogic::createFromBoolean(false);
    }

    /**
     * @inheritDoc
     */
    public function getNonce(): ?string
    {
        return null;
    }
}
