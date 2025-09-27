<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

class Serializer
{
    /**
     * Serialize data
     *
     * @param \Codeception\Module\Percy\Snapshot|array<string, mixed> $data
     * @throws \JsonException
     */
    public function serialize(array | Snapshot $data): string
    {
        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    /**
     * Un-serialize data
     *
     * @return array<string, mixed>
     * @throws \JsonException
     */
    public function unserialize(string $data): array
    {
        /** @var array<string, mixed> $result */
        $result = (array) json_decode($data, true, 512, JSON_THROW_ON_ERROR);

        return $result;
    }
}
