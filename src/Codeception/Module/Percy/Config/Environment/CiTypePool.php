<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment;

use Codeception\Module\Percy\Config\Environment\CiType\CiTypeInterface;
use Codeception\Module\Percy\Config\Environment\Exception\InvalidCiException;

class CiTypePool
{
    /**
     * @var array<string, \Codeception\Module\Percy\Config\Environment\CiType\CiTypeInterface>
     */
    private $ciTypes;

    /**
     * CiTypePool constructor.
     *
     * @param array<string, \Codeception\Module\Percy\Config\Environment\CiType\CiTypeInterface> $ciTypes
     */
    public function __construct(
        array $ciTypes = []
    ) {
        $this->ciTypes = $ciTypes;
    }

    /**
     * Get CI type
     *
     * @throws \Codeception\Module\Percy\Config\Environment\Exception\InvalidCiException
     * @param \Codeception\Module\Percy\Config\Environment\CiType $ciType
     * @return \Codeception\Module\Percy\Config\Environment\CiType\CiTypeInterface
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
     * @return array<string, \Codeception\Module\Percy\Config\Environment\CiType\CiTypeInterface>
     */
    public function getCiTypes(): array
    {
        return $this->ciTypes;
    }
}
