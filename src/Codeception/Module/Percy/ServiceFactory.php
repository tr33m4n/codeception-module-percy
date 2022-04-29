<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

final class ServiceFactory
{
    /**
     * Create instance of a class
     *
     * @param class-string         $className
     * @param array<string, mixed> $parameters
     * @return object
     */
    public function create(string $className, array $parameters = []): object
    {
        return new $className(...$parameters);
    }
}
