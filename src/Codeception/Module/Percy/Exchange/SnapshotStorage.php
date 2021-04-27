<?php
declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\Exception\StorageException;
use Ramsey\Uuid\Uuid;

/**
 * Class SnapshotStorage
 *
 * @package Codeception\Module\Percy\Exchange
 */
class SnapshotStorage
{
    const OUTPUT_FILE_PATTERN = 'dom_snapshots' . DIRECTORY_SEPARATOR . '%s.html';

    /**
     * Save DOM snapshot to file
     *
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @param string $domString
     * @return \Codeception\Module\Percy\Exchange\Snapshot
     */
    public static function save(string $domString) : Snapshot
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

        codecept_debug(sprintf('Writing snapshot to: "%s"', $filePath));

        file_put_contents($filePath, $domString);

        return Snapshot::from($filePath);
    }

    /**
     * Load DOM snapshot from file
     *
     * @param \Codeception\Module\Percy\Exchange\Snapshot $snapshot
     * @return string
     */
    public static function load(Snapshot $snapshot) : string
    {
        codecept_debug(sprintf('Loading snapshot from: "%s"', $snapshot->getFilePath()));

        return file_get_contents($snapshot->getFilePath()) ?: '';
    }

    /**
     * Clean snapshot directory
     */
    public static function clean() : void
    {
        foreach (glob(codecept_output_dir(sprintf(self::OUTPUT_FILE_PATTERN, '*'))) ?: [] as $snapshotFile) {
            codecept_debug(sprintf('Deleting snapshot from: "%s"', $snapshotFile));

            unlink($snapshotFile);
        }
    }
}
