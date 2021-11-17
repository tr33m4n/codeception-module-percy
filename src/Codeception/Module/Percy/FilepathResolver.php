<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

/**
 * Class FilepathResolver
 *
 * @package Codeception\Module\Percy
 */
final class FilepathResolver
{
    /**
     * Get Percy CLI browser JS
     *
     * @return string
     */
    public static function percyCliBrowserJs(): string
    {
        return __DIR__ . '/../../../../node_modules/@percy/dom/dist/bundle.js';
    }

    /**
     * Get Percy CLI executable
     *
     * @return string
     */
    public static function percyCliExecutable(): string
    {
        return __DIR__ . '/../../../../node_modules/.bin/percy';
    }
}
