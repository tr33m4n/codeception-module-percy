<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType;

use Codeception\Module\Percy\Config\Environment\CiType;
use Codeception\Module\Percy\Config\Environment\CiType\GitHub\EventDataProvider;

class GitHub implements CiTypeInterface
{
    /**
     * @var \Codeception\Module\Percy\Config\Environment\CiType\GitHub\EventDataProvider
     */
    private $eventDataProvider;

    /**
     * GitHub constructor.
     *
     * @param \Codeception\Module\Percy\Config\Environment\CiType\GitHub\EventDataProvider $eventDataProvider
     */
    public function __construct(
        EventDataProvider $eventDataProvider
    ) {
        $this->eventDataProvider = $eventDataProvider;
    }

    /**
     * @inheritDoc
     */
    public function getPullRequest(): ?string
    {
        return $this->eventDataProvider->get('pull_request.number');
    }

    /**
     * @inheritDoc
     */
    public function getBranch(): ?string
    {
        return $this->eventDataProvider->get('pull_request.head.ref') ?? $_ENV['GITHUB_REF'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getCommit(): ?string
    {
        return $this->eventDataProvider->get('pull_request.head.sha') ?? $_ENV['GITHUB_SHA'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getInfo(): string
    {
        return isset($_ENV['PERCY_GITHUB_ACTION'])
            ? sprintf('%s/%s', (string) CiType::GITHUB(), $_ENV['PERCY_GITHUB_ACTION'] ?? '')
            : (string) CiType::GITHUB();
    }

    /**
     * @inheritDoc
     */
    public function detect(): bool
    {
        return isset($_ENV['GITHUB_ACTIONS']) && $_ENV['GITHUB_ACTIONS'] === 'true';
    }
}
