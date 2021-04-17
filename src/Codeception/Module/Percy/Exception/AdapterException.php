<?php
declare(strict_types=1);

namespace Codeception\Module\Percy\Exception;

use Codeception\Exception\ModuleException;
use Codeception\Module\Percy;

/**
 * Class AdapterException
 *
 * @package Codeception\Module\Percy\Exception
 */
final class AdapterException extends ModuleException
{
    /**
     * AdapterException constructor.
     *
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct(Percy::NAMESPACE, $message);
    }
}
