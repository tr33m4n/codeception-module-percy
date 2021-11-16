<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Action\Response;

class CreateSnapshot
{
    public const ID_FIELD = 'id';

    /**
     * @var int
     */
    private $id;

    /**
     * CreateSnapshot constructor.
     */
    private function __construct()
    {
        //
    }

    /**
     * Create from response array
     *
     * TODO: Find out what the create snapshot response looks like
     *
     * @param array<string, mixed> $response
     * @return \Codeception\Module\Percy\Exchange\Action\Response\CreateSnapshot
     */
    public static function create(array $response): CreateSnapshot
    {
        $createSnapshot = new self();
        $createSnapshot->id = (int) $response[self::ID_FIELD];

        return $createSnapshot;
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
