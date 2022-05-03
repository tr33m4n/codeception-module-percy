<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exception\StorageException;
use Ramsey\Uuid\Uuid;

class SnapshotRepository
{
    public const STORAGE_FILE_PATTERN = 'dom_snapshots' . DIRECTORY_SEPARATOR . '%s_%s.json';

    private Serializer $serializer;

    private string $instanceId;

    /**
     * SnapshotRepository constructor.
     *
     * @param \Codeception\Module\Percy\Serializer $serializer
     * @param string|null                          $instanceId
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
     * @param \Codeception\Module\Percy\Snapshot $snapshot
     * @return void
     */
    public function save(Snapshot $snapshot): void
    {
        if (!function_exists('codecept_output_dir')) {
            throw new StorageException('`codecept_output_dir` function is not available!');
        }

        $filePath = codecept_output_dir(
            sprintf(self::STORAGE_FILE_PATTERN, $this->instanceId, (string) Uuid::uuid4())
        );

        $fileDirectory = dirname($filePath);
        if (!file_exists($fileDirectory)) {
            mkdir($fileDirectory, 0777, true);
        }

        if (!is_writable($fileDirectory)) {
            chmod($fileDirectory, 0777);
        }

        $writeResults = file_put_contents($filePath, $this->serializer->serialize($snapshot));
        if (!$writeResults) {
            throw new StorageException('Something went wrong when writing the DOM string');
        }
    }

    /**
     * Load snapshot from file
     *
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @throws \JsonException
     * @param string $snapshotFilePath
     * @return \Codeception\Module\Percy\Snapshot
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
     * @param string|null $instanceId
     * @return \Codeception\Module\Percy\Snapshot[]
     */
    public function loadAll(string $instanceId = null): array
    {
        return array_map(
            fn (string $snapshotFile): Snapshot => $this->load($snapshotFile),
            $this->getSnapshotFilePaths($instanceId)
        );
    }

    /**
     * Delete all snapshots
     *
     * @param string|null $instanceId
     */
    public function deleteAll(string $instanceId = null): void
    {
        foreach ($this->getSnapshotFilePaths($instanceId) as $snapshotFile) {
            unlink($snapshotFile);
        }
    }

    /**
     * Get snapshot file paths
     *
     * @param string|null $instanceId
     * @return string[]
     */
    private function getSnapshotFilePaths(string $instanceId = null): array
    {
        return glob(
            codecept_output_dir(
                sprintf(
                    self::STORAGE_FILE_PATTERN,
                    $instanceId ?? $this->instanceId,
                    '*'
                )
            )
        ) ?: [];
    }
}
