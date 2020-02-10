<?php

namespace Codeception\Module\Percy;

use stdClass;

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
     * @return \stdClass
     */
    public static function createClass(string $className, array $additionalArguments = []) : stdClass
    {
        if (!is_a($className, stdClass::class, true)) {
            throw new Exception('Config value is not a class');
        }

        return new $className(...$additionalArguments);
    }
}
