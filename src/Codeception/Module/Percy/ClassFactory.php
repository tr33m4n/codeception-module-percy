<?php

namespace Codeception\Module\Percy;

use InvalidArgumentException;

/**
 * Class ClassFactory
 *
 * @package Codeception\Module\Percy
 */
final class ClassFactory
{
    /**
     * Create class
     *
     * @throws \InvalidArgumentException
     * @param string $className
     * @param array  $additionalArguments
     * @return object
     */
    public static function createClass(string $className, array $additionalArguments = []) : object
    {
        if (!class_exists($className)) {
            throw new InvalidArgumentException('Class does not exist');
        }

        return new $className(...$additionalArguments);
    }
}
