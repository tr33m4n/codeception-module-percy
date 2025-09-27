<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

class Serializer
{
    /**
     * Serialize data
     *
     * @throws \JsonException
     * @param array<string, mixed>|\Codeception\Module\Percy\Snapshot $data
     */
    public function serialize($data): string
    {
        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    /**
     * Un-serialize data
     *
     * @throws \JsonException
     * @return array<string, mixed>
     */
    public function unserialize(string $data): array
    {
        /** @var array<string, mixed> $result */
        $result = (array) json_decode($data, true, 512, JSON_THROW_ON_ERROR);

        return $result;
    }
}
