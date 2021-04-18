<?php
declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\Exception\StorageException;

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

        $filePath = codecept_output_dir(sprintf(self::OUTPUT_FILE_PATTERN, crc32($domString)));
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
        return file_get_contents($snapshot->getFilePath()) ?: '';
    }

    /**
     * Delete by snapshot
     *
     * @param \Codeception\Module\Percy\Exchange\Snapshot|null $snapshot
     */
    public static function delete(?Snapshot $snapshot) : void
    {
        if (!$snapshot || !is_file($snapshot->getFilePath())) {
            return;
        }

        unlink($snapshot->getFilePath());
    }
}
