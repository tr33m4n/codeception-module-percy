<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Action\Response;

class FinalizeSnapshot
{
    /**
     * FinalizeSnapshot constructor.
     */
    private function __construct()
    {
        //
    }

    /**
     * Create from response array
     *
     * TODO: Find out what the finalize snapshot response looks like
     *
     * @param array<string, mixed> $response
     * @return \Codeception\Module\Percy\Exchange\Action\Response\FinalizeSnapshot
     */
    public static function create(array $response): FinalizeSnapshot
    {
        return new self();
    }
}
