<?php

namespace Codeception\Module\Percy;

use Exception;

/**
 * Class ClassFactory
 *
 * @package Codeception\Module\Percy
 */
class ClassFactory
{
    /**
     * Create class
     *
     * @throws \Exception
     * @param string $className
     * @param array  $additionalArguments
     * @return object
     */
    public static function createClass(string $className, array $additionalArguments = []) : object
    {
        if (!class_exists($className)) {
            throw new Exception('Class does not exist');
        }

        return new $className(...$additionalArguments);
    }
}
