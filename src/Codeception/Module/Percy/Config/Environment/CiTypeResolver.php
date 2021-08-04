<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment;

use Codeception\Module\Percy\Config\Environment\CiType\CiTypeInterface;
use Codeception\Module\Percy\Config\Environment\CiType\Unknown;

class CiTypeResolver
{
    /**
     * @var \Codeception\Module\Percy\Config\Environment\CiTypePool
     */
    private $ciTypePool;

    /**
     * CiTypeResolver constructor.
     *
     * @param \Codeception\Module\Percy\Config\Environment\CiTypePool $ciTypePool
     */
    public function __construct(
        CiTypePool $ciTypePool
    ) {
        $this->ciTypePool = $ciTypePool;
    }

    /**
     * Resolve CI type
     *
     * @return \Codeception\Module\Percy\Config\Environment\CiType\CiTypeInterface
     */
    public function resolve() : CiTypeInterface
    {
        foreach ($this->ciTypePool->getCiTypes() as $ciType) {
            if (!$ciType->detect()) {
                continue;
            }

            return $ciType;
        }

        return new Unknown();
    }
}
