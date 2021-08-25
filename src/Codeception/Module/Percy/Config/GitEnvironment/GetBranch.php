<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\GitEnvironment;

use Symfony\Component\Process\Process;

class GetBranch
{
    /**
     * @var null|string
     */
    private $branch;

    /**
     * Get branch
     *
     * @throws \ReflectionException
     * @throws \tr33m4n\Di\Exception\MissingClassException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @throws \tr33m4n\Utilities\Exception\ConfigException
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException
     * @return string
     */
    public function execute(): string
    {
        if (null !== $this->branch) {
            return $this->branch;
        }

        /** @var \Symfony\Component\Process\Process<string, mixed> $process */
        $process = container()->create(
            Process::class,
            [
                'command' => [
                    'git',
                    'rev-parse',
                    '--abbrev-ref',
                    'HEAD'
                ]
            ]
        );

        $process->mustRun();

        return $this->branch = $process->getOutput();
    }
}
