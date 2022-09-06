<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Command;

use Codeception\CustomCommandInterface;
use Codeception\Lib\Console\Output;
use Codeception\Module\Percy\Definitions;
use Codeception\Module\Percy\ServiceContainer;
use Codeception\Util\Debug;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ProcessSnapshots extends Command implements CustomCommandInterface
{
    private const LOAD_PATH_TEMPLATE_OPTION = 'template';

    private const SUPPRESS_THROW_OPTION = 'suppress_throw';

    /**
     * @inheritDoc
     */
    public static function getCommandName(): string
    {
        return 'percy:process-snapshots';
    }

    /**
     * Get default description
     */
    public static function getDefaultDescription(): string
    {
        return 'Process any snapshots that exist in the snapshot directory, then cleanup';
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->addOption(
            self::LOAD_PATH_TEMPLATE_OPTION,
            't',
            InputArgument::OPTIONAL,
            'Pass a path template to use when loading snapshots. This will be resolved from the Codeception config root'
        );

        $this->addOption(
            self::SUPPRESS_THROW_OPTION,
            'e',
            InputArgument::OPTIONAL,
            'Whether to suppress throwing and exiting with an error, printing the error instead',
            false
        );
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
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string|null $loadPathTemplate */
        $loadPathTemplate = $input->getOption(self::LOAD_PATH_TEMPLATE_OPTION);

        $serviceContainer = new ServiceContainer(null, Definitions::DEFAULT_CONFIG);
        $snapshotManagement = $serviceContainer->getSnapshotManagement($loadPathTemplate);

        // Codeception uses its own "output" when configuring the `debug` methods. Create a new instance
        $codeceptionOutputInstance = new Output([]);

        $io = new SymfonyStyle($input, $codeceptionOutputInstance);
        Debug::setOutput($codeceptionOutputInstance);

        try {
            $snapshotManagement->sendAll();
            $snapshotManagement->resetAll();

            $io->success('Successfully processed snapshots');
        } catch (Exception $exception) {
            if (false === $input->getOption(self::SUPPRESS_THROW_OPTION)) {
                throw $exception;
            }
            $output->writeln('Process snapshot command errored silently with the following:');
            $output->writeln($exception->getMessage());
        }

        return self::SUCCESS;
    }
}
