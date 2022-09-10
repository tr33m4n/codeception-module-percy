<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exception\MissingPercyTokenException;
use Codeception\Module\Percy\Exception\PercyDisabledException;

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
     * @throws \Codeception\Module\Percy\Exception\MissingPercyTokenException
     * @throws \Codeception\Module\Percy\Exception\PercyDisabledException
     */
    public function execute(): void
    {
        if (!$this->configManagement->isEnabled()) {
            throw new PercyDisabledException('Percy has been disabled through environment configuration');
        }

        if (!$this->configManagement->hasPercyToken()) {
            throw new MissingPercyTokenException('Percy token has not been set');
        }
    }
}
