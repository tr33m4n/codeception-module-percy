<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exception\StorageException;
use Ramsey\Uuid\Uuid;

class CreateSnapshot
{
    public const OUTPUT_FILE_PATTERN = 'dom_snapshots' . DIRECTORY_SEPARATOR . '%s.html';

    /**
     * Create snapshot from DOM string
     *
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @throws \Exception
     * @param string $domString
     * @return \Codeception\Module\Percy\Snapshot
     */
    public function execute(string $domString): Snapshot
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

        $writeResults = file_put_contents($filePath, $domString);
        if (!$writeResults) {
            throw new StorageException('Something went wrong when writing the DOM string');
        }

        return Snapshot::create($filePath);
    }
}
