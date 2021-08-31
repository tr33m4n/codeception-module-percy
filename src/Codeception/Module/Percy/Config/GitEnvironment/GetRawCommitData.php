<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\GitEnvironment;

use Symfony\Component\Process\Process;

class GetRawCommitData
{
    private const COMMIT_FORMAT = [
        'COMMIT_SHA:%H',
        'AUTHOR_NAME:%an',
        'AUTHOR_EMAIL:%ae',
        'COMMITTER_NAME:%cn',
        'COMMITTER_EMAIL:%ce',
        'COMMITTED_DATE:%ai',
        'COMMIT_MESSAGE:%B'
    ];

    /**
     * @var null|string
     */
    private $rawCommitData;

    /**
     * Get raw commit data
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
        if (null !== $this->rawCommitData) {
            return $this->rawCommitData;
        }

        /** @var \Symfony\Component\Process\Process<string, mixed> $process */
        $process = container()->create(
            Process::class,
            [
                'command' => [
                    'git',
                    'show',
                    'HEAD',
                    '--quiet',
                    sprintf('--format=%s', implode('%n', self::COMMIT_FORMAT))
                ]
            ]
        );

        return $this->rawCommitData = $process->mustRun()->getOutput();
    }
}
