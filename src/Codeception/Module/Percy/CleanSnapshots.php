<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

class CleanSnapshots
{
    private ConfigManagement $configManagement;

    /**
     * SnapshotManagement constructor.
     *
     * @param \Codeception\Module\Percy\ConfigManagement $configManagement
     */
    public function __construct(
        ConfigManagement $configManagement
    ) {
        $this->configManagement = $configManagement;
    }

    /**
     * Clean snapshot directory
     */
    public function execute(): void
    {
        if (!$this->configManagement->shouldCleanSnapshotStorage()) {
            return;
        }

        foreach (glob(codecept_output_dir(sprintf(CreateSnapshot::OUTPUT_FILE_PATTERN, '*'))) ?: [] as $snapshotFile) {
            unlink($snapshotFile);
        }
    }
}
