<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Command;

use Codeception\CustomCommandInterface;
use Codeception\Module\Percy\Definitions;
use Codeception\Module\Percy\ServiceContainer;
use Codeception\Module\Percy\SnapshotManagement;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ProcessSnapshots extends Command implements CustomCommandInterface
{
    private SnapshotManagement $snapshotManagement;

    /**
     * ProcessSnapshots constructor.
     *
     * @param string|null $name
     */
    public function __construct(
        string $name = null
    ) {
        $serviceContainer = new ServiceContainer(null, Definitions::DEFAULT_CONFIG);
        $this->snapshotManagement = $serviceContainer->getSnapshotManagement();

        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    public static function getCommandName(): string
    {
        return 'percy:process-snapshots';
    }

    /**
     * Get default description
     *
     * @return string
     */
    public static function getDefaultDescription(): string
    {
        return 'Process any snapshots that exist in the snapshot directory, then cleanup';
    }

    /**
     * {@inheritdoc}
     *
     * Process snapshots
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @throws \JsonException
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->snapshotManagement->sendAll();
        $this->snapshotManagement->resetAll();

        $io->success('Successfully processed snapshots');

        return self::SUCCESS;
    }
}
