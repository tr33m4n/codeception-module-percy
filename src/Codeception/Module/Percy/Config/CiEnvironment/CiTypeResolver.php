<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\CiEnvironment;

use Codeception\Module\Percy\Config\CiEnvironment\CiType\CiTypeInterface;
use Codeception\Module\Percy\Config\CiEnvironment\CiType\Unknown;
use OndraM\CiDetector\Env as CiDetectorEnv;

class CiTypeResolver
{
    /**
     * @var \Codeception\Module\Percy\Config\CiEnvironment\CiTypePool
     */
    private $ciTypePool;

    /**
     * @var \OndraM\CiDetector\Env
     */
    private $ciDetectorEnv;

    /**
     * CiTypeResolver constructor.
     *
     * @param \Codeception\Module\Percy\Config\CiEnvironment\CiTypePool $ciTypePool
     * @param \OndraM\CiDetector\Env                                    $ciDetectorEnv
     */
    public function __construct(
        CiTypePool $ciTypePool,
        CiDetectorEnv $ciDetectorEnv
    ) {
        $this->ciTypePool = $ciTypePool;
        $this->ciDetectorEnv = $ciDetectorEnv;
    }

    /**
     * Resolve CI type
     *
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType\CiTypeInterface
     */
    public function resolve(): CiTypeInterface
    {
        foreach ($this->ciTypePool->getCiTypes() as $ciType) {
            if (!$ciType->isDetected($this->ciDetectorEnv)) {
                continue;
            }

            return $ciType;
        }

        return new Unknown();
    }
}
