<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config;

use Codeception\Module\Percy\Config\Environment\CiEnvironment;

class Provider
{
    /**
     * @var \Codeception\Module\Percy\Config\Environment\CiEnvironment
     */
    private $ciEnvironment;

    /**
     * Provider constructor.
     *
     * @param \Codeception\Module\Percy\Config\Environment\CiEnvironment $ciEnvironment
     */
    public function __construct(
        CiEnvironment $ciEnvironment
    ) {
        $this->ciEnvironment = $ciEnvironment;
    }

    /**
     * Get CI environment
     *
     * @return \Codeception\Module\Percy\Config\Environment\CiEnvironment
     */
    public function getCiEnvironment(): CiEnvironment
    {
        return $this->ciEnvironment;
    }
}
