<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Persistence;

use JsonSerializable;

class Dom implements JsonSerializable
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * Dom constructor.
     */
    private function __construct()
    {
        //
    }

    /**
     * From file path
     *
     * @param string $filePath
     * @return \Codeception\Module\Percy\Persistence\Dom
     */
    public static function from(string $filePath): Dom
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
        return (string) $this;
    }

    /**
     * Load content when cast to string
     *
     * @return string
     */
    public function __toString(): string
    {
        return file_get_contents($this->getFilePath()) ?: '';
    }
}
