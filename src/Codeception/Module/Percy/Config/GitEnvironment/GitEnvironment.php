<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\GitEnvironment;

class GitEnvironment
{
    /**
     * @var \Codeception\Module\Percy\Config\GitEnvironment\GetBranch
     */
    private $getBranch;

    /**
     * @var \Codeception\Module\Percy\Config\GitEnvironment\GetValue
     */
    private $getValue;

    /**
     * GitEnvironment constructor.
     *
     * @param \Codeception\Module\Percy\Config\GitEnvironment\GetBranch $getBranch
     * @param \Codeception\Module\Percy\Config\GitEnvironment\GetValue  $getValue
     */
    public function __construct(
        GetBranch $getBranch,
        GetValue $getValue
    ) {
        $this->getBranch = $getBranch;
        $this->getValue = $getValue;
    }

    /**
     * Get SHA
     *
     * @throws \ReflectionException
     * @throws \tr33m4n\Di\Exception\MissingClassException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @throws \tr33m4n\Utilities\Exception\ConfigException
     * @return string|null
     */
    public function getSha(): ?string
    {
        return $this->getValue->execute('COMMIT_SHA');
    }

    /**
     * Get branch
     *
     * @throws \ReflectionException
     * @throws \tr33m4n\Di\Exception\MissingClassException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @throws \tr33m4n\Utilities\Exception\ConfigException
     * @return string|null
     */
    public function getBranch(): ?string
    {
        return $this->getBranch->execute();
    }

    /**
     * Get message
     *
     * @throws \ReflectionException
     * @throws \tr33m4n\Di\Exception\MissingClassException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @throws \tr33m4n\Utilities\Exception\ConfigException
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->getValue->execute('COMMIT_MESSAGE');
    }

    /**
     * Get author name
     *
     * @throws \ReflectionException
     * @throws \tr33m4n\Di\Exception\MissingClassException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @throws \tr33m4n\Utilities\Exception\ConfigException
     * @return string|null
     */
    public function getAuthorName(): ?string
    {
        return $this->getValue->execute('AUTHOR_NAME');
    }

    /**
     * Get author email
     *
     * @throws \ReflectionException
     * @throws \tr33m4n\Di\Exception\MissingClassException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @throws \tr33m4n\Utilities\Exception\ConfigException
     * @return string|null
     */
    public function getAuthorEmail(): ?string
    {
        return $this->getValue->execute('AUTHOR_EMAIL');
    }

    /**
     * Get committed at
     *
     * @throws \ReflectionException
     * @throws \tr33m4n\Di\Exception\MissingClassException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @throws \tr33m4n\Utilities\Exception\ConfigException
     * @return string|null
     */
    public function getCommittedAt(): ?string
    {
        return $this->getValue->execute('COMMITTED_DATE');
    }

    /**
     * Get committer name
     *
     * @throws \ReflectionException
     * @throws \tr33m4n\Di\Exception\MissingClassException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @throws \tr33m4n\Utilities\Exception\ConfigException
     * @return string|null
     */
    public function getCommitterName(): ?string
    {
        return $this->getValue->execute('COMMITTER_NAME');
    }

    /**
     * Get committer email
     *
     * @throws \ReflectionException
     * @throws \tr33m4n\Di\Exception\MissingClassException
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     * @throws \tr33m4n\Utilities\Exception\ConfigException
     * @return string|null
     */
    public function getCommitterEmail(): ?string
    {
        return $this->getValue->execute('COMMITTER_EMAIL');
    }
}
