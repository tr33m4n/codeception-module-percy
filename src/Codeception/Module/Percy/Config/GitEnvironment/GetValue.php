<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\GitEnvironment;

class GetValue
{
    /**
     * @var \Codeception\Module\Percy\Config\GitEnvironment\GetRawCommitData
     */
    private $getRawCommitData;

    /**
     * GetValue constructor.
     *
     * @param \Codeception\Module\Percy\Config\GitEnvironment\GetRawCommitData $getRawCommitData
     */
    public function __construct(
        GetRawCommitData $getRawCommitData
    ) {
        $this->getRawCommitData = $getRawCommitData;
    }

    /**
     * Get value
     *
     * @throws \ReflectionException
     * @throws \tr33m4n\Di\Exception\MissingClassException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @throws \tr33m4n\Utilities\Exception\ConfigException
     * @param string $value
     * @return string|null
     */
    public function execute(string $value): ?string
    {
        if (!preg_match(sprintf('/%s:(.*)/m', $value), $this->getRawCommitData->execute(), $result)) {
            return null;
        }

        return $result[1] ?? null;
    }
}
