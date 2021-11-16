<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Action\Response;

class FinalizeBuild
{
    /**
     * FinalizeBuild constructor.
     */
    private function __construct()
    {
        //
    }

    /**
     * Create from response array
     *
     * TODO: Find out what the finalize build response looks like
     *
     * @param array<string, mixed> $response
     * @return \Codeception\Module\Percy\Exchange\Action\Response\FinalizeBuild
     */
    public static function create(array $response): FinalizeBuild
    {
        return new self();
    }
}
