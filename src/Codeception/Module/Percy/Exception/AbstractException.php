<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exception;

use Codeception\Exception\ModuleException;
use Codeception\Module\Percy\Definitions;

abstract class AbstractException extends ModuleException
{
    /**
     * AbstractException constructor.
     *
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct(Definitions::NAMESPACE, $message);
    }
}
