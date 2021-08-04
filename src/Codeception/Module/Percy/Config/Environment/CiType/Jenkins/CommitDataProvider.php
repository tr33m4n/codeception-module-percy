<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType\Jenkins;

class CommitDataProvider
{
    private $gitCommand;

    public function __construct(
        GitCommand $gitCommand
    ) {
        $this->gitCommand = $gitCommand;
    }

    public function execute() : array
    {
        $rawData = $this->gitCommand->execute(
            sprintf('show HEAD --quiet --format=%s', $_ENV['GIT_COMMIT_FORMAT'] ?? '')
        );

        $get = function (string $key) use ($rawData) {
            return preg_match_all(sprintf('/%s:(.*)/m', $key), $rawData);
        };
    }
}
