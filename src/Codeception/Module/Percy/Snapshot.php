<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use JsonSerializable;

class Snapshot implements JsonSerializable
{
    private string $filePath;

    /**
     * Snapshot constructor.
     */
    private function __construct()
    {
        //
    }

    /**
     * From file path
     *
     * @param string $filePath
     * @return \Codeception\Module\Percy\Snapshot
     */
    public static function from(string $filePath): Snapshot
    {
        $snapshot = new self();
        $snapshot->filePath = $filePath;

        return $snapshot;
    }

    /**
     * Get file path
     *
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string
    {
        return file_get_contents($this->getFilePath()) ?: '';
    }
}
