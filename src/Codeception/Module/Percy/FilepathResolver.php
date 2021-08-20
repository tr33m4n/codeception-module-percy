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
     * Get Percy agent browser JS
     *
     * @return string
     */
    public function percyAgentBrowserJs(): string
    {
        return __DIR__ . '/../../../../node_modules/@percy/agent/dist/public/percy-agent.js';
    }

    /**
     * Get Percy agent executable
     *
     * @return string
     */
    public function percyAgentExecutable(): string
    {
        return __DIR__ . '/../../../../node_modules/.bin/percy';
    }
}
