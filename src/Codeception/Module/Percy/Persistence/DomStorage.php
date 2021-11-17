<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Persistence;

use Codeception\Module\Percy\Exception\StorageException;
use Codeception\Module\Percy\Exchange\Action\Request\Snapshot;
use Ramsey\Uuid\Uuid;

class DomStorage
{
    public const OUTPUT_FILE_PATTERN = 'dom_snapshots' . DIRECTORY_SEPARATOR . '%s.html';

    /**
     * Save DOM snapshot to file
     *
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @param \Codeception\Module\Percy\Exchange\Action\Request\Snapshot $snapshot
     * @param string                                                     $domString
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public function save(Snapshot $snapshot, string $domString): Snapshot
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

        return $snapshot->withDomSnapshot(Dom::from($filePath));
    }

    /**
     * Clean snapshot directory
     *
     * @throws \tr33m4n\Utilities\Exception\AdapterException
     */
    public function clean(): void
    {
        if (!config('percy')->get('cleanSnapshotStorage')) {
            return;
        }

        foreach (glob(codecept_output_dir(sprintf(self::OUTPUT_FILE_PATTERN, '*'))) ?: [] as $domFile) {
            unlink($domFile);
        }
    }
}
