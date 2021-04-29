<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

/**
 * Class ConfigProvider
 *
 * @package Codeception\Module\Percy
 */
class ConfigProvider
{
    /**
     * @var array<string, mixed> $config
     */
    private static $config;

    /**
     * Set config
     *
     * @param array<string, mixed> $config
     */
    public static function set(array $config): void
    {
        self::$config = $config;
    }

    /**
     * Get config
     *
     * @param string|null $key
     * @return mixed|null
     */
    public static function get(string $key = null)
    {
        if (!$key) {
            return self::$config;
        }

        if (isset(self::$config[$key])) {
            return self::$config[$key];
        }

        return null;
    }
}
