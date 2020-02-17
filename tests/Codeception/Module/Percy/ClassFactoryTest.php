<?php

namespace Codeception\Module\Percy;

use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

/**
 * Class ClassFactoryTest
 *
 * @package Codeception\Module\Percy
 */
class ClassFactoryTest extends TestCase
{
    /**
     * Test creating a class that exists
     *
     * @author Daniel Doyle <dd@amp.co>
     */
    public function testCreatingAClassThatExists() : void
    {
        $this->assertInstanceOf(ClassFactory::class, ClassFactory::createClass(ClassFactory::class));
    }

    /**
     * Test creating a class that does not exist
     *
     * @author Daniel Doyle <dd@amp.co>
     */
    public function testCreatingAClassThatDoesNotExist() : void
    {
        $this->expectException(InvalidArgumentException::class);

        ClassFactory::createClass('\\Some\\Class\\That\\Does\\Not\\Exist');
    }
}
