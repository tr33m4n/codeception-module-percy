<?php

namespace Codeception\Module\Percy\Test;

use Codeception\Module\Percy\Payload;
use Codeception\Test\Unit;
use InvalidArgumentException;

/**
 * Class PayloadTest
 */
class PayloadTest extends Unit
{
    /**
     * Test that an invalid key cannot be set against the payload object
     */
    public function testCannotAddAnInvalidKey(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Payload::from(['invalid' => 'key']);
    }

    /**
     * Test that the payload can be cast to a JSON string
     */
    public function testCanBeCastToJson(): void
    {
        $this->assertEquals(
            '{"enableJavaScript":true,"name":"Test","url":"https:\/\/example.com"}',
            (string) Payload::from([
                Payload::ENABLE_JAVASCRIPT => true
            ])->withName('Test')->withUrl('https://example.com')
        );
    }
}
