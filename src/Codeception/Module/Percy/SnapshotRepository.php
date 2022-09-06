<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exception\StorageException;
use Ramsey\Uuid\Uuid;

class SnapshotRepository
{
    public const FILE_TEMPLATE = '%s_%s.json';

    private Serializer $serializer;

    private string $instanceId;

    /**
     * SnapshotRepository constructor.
     */
    public function __construct(
        Serializer $serializer,
        ?string $instanceId = null
    ) {
        $this->serializer = $serializer;
        // Ensure we're only managing snapshots created by this test run by prepending with an "instance ID"
        $this->instanceId = $instanceId ?? (string) Uuid::uuid4();
    }

    /**
     * Save snapshot
     *
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @throws \JsonException
     */
    public function save(Snapshot $snapshot): void
    {
        if (!function_exists('codecept_output_dir')) {
            throw new StorageException('`codecept_output_dir` function is not available!');
        }

        $writeResults = file_put_contents($this->buildFilePath(), $this->serializer->serialize($snapshot));
        if (!$writeResults) {
            throw new StorageException('Something went wrong when writing the DOM string');
        }
    }

    /**
     * Load snapshot from file
     *
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @throws \JsonException
     */
    public function load(string $snapshotFilePath): Snapshot
    {
        $snapshotFileContents = file_get_contents($snapshotFilePath);
        if (!$snapshotFileContents) {
            throw new StorageException(sprintf('Unable to load the snapshot file "%s"', $snapshotFilePath));
        }

        /** @var array<string, string> $decodedSnapshotFileContents */
        $decodedSnapshotFileContents = $this->serializer->unserialize($snapshotFileContents);

        return Snapshot::hydrate($decodedSnapshotFileContents);
    }

    /**
     * Load all snapshots
     *
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @throws \JsonException
     * @return \Codeception\Module\Percy\Snapshot[]
     */
    public function loadAll(string $instanceId = null, string $snapshotFolder = null): array
    {
        return array_map(
            fn (string $snapshotFile): Snapshot => $this->load($snapshotFile),
            $this->getSnapshotFilePaths($instanceId, $snapshotFolder)
        );
    }

    /**
     * Delete all snapshots
     */
    public function deleteAll(string $instanceId = null, string $snapshotFolder = null): void
    {
        foreach ($this->getSnapshotFilePaths($instanceId, $snapshotFolder) as $snapshotFile) {
            unlink($snapshotFile);
        }
    }

    /**
     * Get snapshot file paths
     *
     * @param string|null $instanceId
     * @return string[]
     */
    private function getSnapshotFilePaths(string $instanceId = null, ?string $snapshotFolder = null): array
    {
        return glob($this->buildFilePath($instanceId, '*', $snapshotFolder)) ?: [];
    }

    /**
     * Build file path
     */
    private function buildFilePath(
        ?string $instanceId = null,
        ?string $snapshotId = null,
        ?string $snapshotFolder = null
    ): string {
        $filePath = $snapshotFolder
            ? codecept_root_dir(trim($snapshotFolder, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . self::FILE_TEMPLATE)
            : codecept_output_dir('dom_snapshots' . DIRECTORY_SEPARATOR . self::FILE_TEMPLATE);

        return $this->verifyFilePath(
            sprintf(
                $filePath,
                $instanceId ?? $this->instanceId,
                $snapshotId ?? (string) Uuid::uuid1()
            )
        );
    }

    /**
     * Verify file path
     */
    private function verifyFilePath(string $filePath): string
    {
        $fileDirectory = dirname($filePath);
        if (!file_exists($fileDirectory)) {
            mkdir($fileDirectory, 0777, true);
        }

        if (!is_writable($fileDirectory)) {
            chmod($fileDirectory, 0777);
        }

        return $filePath;
    }
}
