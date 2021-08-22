<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\CiEnvironment;

use Codeception\Module\Percy\Config\CiEnvironment\CiType\CiTypeInterface;
use Codeception\Module\Percy\Config\CiEnvironment\Exception\InvalidCiException;

class CiTypePool
{
    /**
     * @var array<string, \Codeception\Module\Percy\Config\CiEnvironment\CiType\CiTypeInterface>
     */
    private $ciTypes;

    /**
     * CiTypePool constructor.
     *
     * @param array<string, \Codeception\Module\Percy\Config\CiEnvironment\CiType\CiTypeInterface> $ciTypes
     */
    public function __construct(
        array $ciTypes = []
    ) {
        $this->ciTypes = $ciTypes;
    }

    /**
     * Get CI type
     *
     * @throws \Codeception\Module\Percy\Config\CiEnvironment\Exception\InvalidCiException
     * @param \Codeception\Module\Percy\Config\CiEnvironment\CiType $ciType
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType\CiTypeInterface
     */
    public function getCiType(CiType $ciType): CiTypeInterface
    {
        if (!array_key_exists((string) $ciType, $this->ciTypes)) {
            throw new InvalidCiException(sprintf('"%s" is not a valid CI type', (string) $ciType));
        }

        return $this->ciTypes[(string) $ciType];
    }

    /**
     * Get CI types
     *
     * @return array<string, \Codeception\Module\Percy\Config\CiEnvironment\CiType\CiTypeInterface>
     */
    public function getCiTypes(): array
    {
        return $this->ciTypes;
    }
}
