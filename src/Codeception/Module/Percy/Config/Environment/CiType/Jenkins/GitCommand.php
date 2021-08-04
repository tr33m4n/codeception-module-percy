<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment\CiType\Jenkins;

use Symfony\Component\Process\Process;

class GitCommand
{
    /**
     * Execute Git command
     *
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException
     * @param string $args
     * @return string
     */
    public function execute(string $args) : string
    {
        return Process::fromShellCommandline(sprintf('git %s', $args))->mustRun()->getOutput();
    }
}
