<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exception\StorageException;
use Ramsey\Uuid\Uuid;

class SnapshotRepository
{
    public const STORAGE_FILE_PATTERN = 'dom_snapshots' . DIRECTORY_SEPARATOR . '%s_%s.json';

    private string $instanceId;

    /**
     * SnapshotRepository constructor.
     *
     * @param string|null $instanceId
     */
    public function __construct(
        string $instanceId = null
    ) {
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

        $writeResults = file_put_contents($filePath, json_encode($snapshot, JSON_THROW_ON_ERROR));
        if (!$writeResults) {
            throw new StorageException('Something went wrong when writing the DOM string');
        }
    }

    /**
     * Load snapshot from file
     *
     * @throws \JsonException
     * @param string $snapshotFile
     * @return \Codeception\Module\Percy\Snapshot
     */
    public function load(string $snapshotFile): Snapshot
    {
        /** @var array<string, string> $decodedSnapshotFile */
        $decodedSnapshotFile = (array) json_decode(
            file_get_contents($snapshotFile) ?: '',
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        return Snapshot::create(
            $decodedSnapshotFile[Snapshot::DOM_SNAPSHOT],
            $decodedSnapshotFile[Snapshot::NAME],
            $decodedSnapshotFile[Snapshot::URL],
            $decodedSnapshotFile[Snapshot::CLIENT_INFO],
            $decodedSnapshotFile[Snapshot::ENVIRONMENT_INFO],
            array_diff_key(
                $decodedSnapshotFile,
                [
                    Snapshot::DOM_SNAPSHOT,
                    Snapshot::NAME,
                    Snapshot::URL,
                    Snapshot::CLIENT_INFO,
                    Snapshot::ENVIRONMENT_INFO
                ]
            )
        );
    }

    /**
     * Load all snapshots
     *
     * @throws \JsonException
     * @return \Codeception\Module\Percy\Snapshot[]
     */
    public function loadAll(): array
    {
        return array_map(function (string $snapshotFile): Snapshot {
            return $this->load($snapshotFile);
        }, $this->getSnapshotFilePaths());
    }

    /**
     * Delete all snapshots
     */
    public function deleteAll(): void
    {
        foreach ($this->getSnapshotFilePaths() as $snapshotFile) {
            unlink($snapshotFile);
        }
    }

    /**
     * Get snapshot file paths
     *
     * @return string[]
     */
    private function getSnapshotFilePaths(): array
    {
        return glob(codecept_output_dir(sprintf(self::STORAGE_FILE_PATTERN, $this->instanceId, '*'))) ?: [];
    }
}
