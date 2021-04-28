<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exception\StorageException;
use Ramsey\Uuid\Uuid;

/**
 * Class SnapshotManagement
 *
 * @package Codeception\Module\Percy
 */
class SnapshotManagement
{
    public const OUTPUT_FILE_PATTERN = 'dom_snapshots' . DIRECTORY_SEPARATOR . '%s.html';

    /**
     * Save DOM snapshot to file
     *
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @throws \Exception
     * @param string $domString
     * @return \Codeception\Module\Percy\Snapshot
     */
    public static function save(string $domString): Snapshot
    {
        if (!function_exists('codecept_output_dir')) {
            throw new StorageException('`codecept_output_dir` function is not available!');
        }

        $filePath = codecept_output_dir(sprintf(self::OUTPUT_FILE_PATTERN, Uuid::uuid4()->toString()));

        $fileDirectory = dirname($filePath);
        if (!file_exists($fileDirectory)) {
            mkdir($fileDirectory, 0777, true);
        }

        if (!is_writable($fileDirectory)) {
            chmod($fileDirectory, 0777);
        }

        file_put_contents($filePath, $domString);

        return Snapshot::from($filePath);
    }

    /**
     * Load DOM snapshot from file
     *
     * @param \Codeception\Module\Percy\Snapshot $snapshot
     * @return string
     */
    public static function load(Snapshot $snapshot): string
    {
        return file_get_contents($snapshot->getFilePath()) ?: '';
    }

    /**
     * Clean snapshot directory
     */
    public static function clean(): void
    {
        foreach (glob(codecept_output_dir(sprintf(self::OUTPUT_FILE_PATTERN, '*'))) ?: [] as $snapshotFile) {
            unlink($snapshotFile);
        }
    }
}
