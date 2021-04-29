<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use InvalidArgumentException;

/**
 * Class ValidatePayloadKey
 *
 * @package Codeception\Module\Percy\Exchange
 */
class ValidatePayloadKey
{
    /**
     * Array of keys that can be set from config
     */
    public const PUBLIC_KEYS = [
        Payload::PERCY_CSS,
        Payload::MIN_HEIGHT,
        Payload::ENABLE_JAVASCRIPT,
        Payload::WIDTHS
    ];

    /**
     * Validate key
     *
     * @param string $key
     */
    public static function execute(string $key): void
    {
        if (!in_array($key, self::PUBLIC_KEYS)) {
            throw new InvalidArgumentException(
                sprintf('"%s" cannot be set through config', $key)
            );
        }
    }
}
