<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment;

class CiEnvironmentFactory
{
    /**
     * @var \Codeception\Module\Percy\Config\Environment\CiTypeResolver
     */
    private $ciTypeResolver;

    /**
     * CiEnvironmentFactory constructor.
     *
     * @param \Codeception\Module\Percy\Config\Environment\CiTypeResolver $ciTypeResolver
     */
    public function __construct(
        CiTypeResolver $ciTypeResolver
    ) {
        $this->ciTypeResolver = $ciTypeResolver;
    }

    /**
     * Create CI environment
     *
     * @return \Codeception\Module\Percy\Config\Environment\CiEnvironment
     */
    public function create() : CiEnvironment
    {
        return new CiEnvironment($this->ciTypeResolver->resolve());
    }
}
