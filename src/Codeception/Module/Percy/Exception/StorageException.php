<?php
declare(strict_types=1);

namespace Codeception\Module\Percy\Exception;

use Codeception\Exception\ModuleException;
use Codeception\Module\Percy;

/**
 * Class StorageException
 *
 * @package Codeception\Module\Percy\Exception
 */
final class StorageException extends ModuleException
{
    /**
     * StorageException constructor.
     *
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct(Percy::NAMESPACE, $message);
    }
}
