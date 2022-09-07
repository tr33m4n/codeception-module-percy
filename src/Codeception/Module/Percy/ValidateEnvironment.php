<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exception\EnvironmentException;

class ValidateEnvironment
{
    private ConfigManagement $configManagement;

    /**
     * ValidateEnvironment constructor.
     */
    public function __construct(
        ConfigManagement $configManagement
    ) {
        $this->configManagement = $configManagement;
    }

    /**
     * Validate environment
     *
     * @throws \Codeception\Module\Percy\Exception\EnvironmentException
     */
    public function execute(): void
    {
        if (!$this->configManagement->isEnabled()) {
            throw new EnvironmentException('Percy has been disabled through environment configuration');
        }

        if (!$this->configManagement->hasPercyToken()) {
            throw new EnvironmentException('Percy token has not been set');
        }
    }
}
