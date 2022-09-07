<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Command;

use Codeception\Command\Shared\Config;
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

class ProcessSnapshots extends Command implements CustomCommandInterface
{
    use Config;

    private const SUITE_ARGUMENT = 'suite';

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
        $this->addArgument(
            self::SUITE_ARGUMENT,
            InputArgument::REQUIRED,
            'Suite to use when loading configuration'
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
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $modulesConfig = $this->getSuiteConfig($input->getArgument(self::SUITE_ARGUMENT))['modules'] ?? [];
        // Codeception uses its own "output" when configuring the `debug` methods. Create a new instance
        Debug::setOutput(new Output($this->getGlobalConfig()));

        $serviceContainer = new ServiceContainer(
            null,
            array_merge(Definitions::DEFAULT_CONFIG, $modulesConfig['config'][Definitions::NAMESPACE] ?? [])
        );

        $snapshotManagement = $serviceContainer->getSnapshotManagement();
        $configManagement = $serviceContainer->getConfigManagement();
        $outputService = $serviceContainer->getOutput();

        if (!in_array(Definitions::NAMESPACE, $modulesConfig['enabled'] ?? [])) {
            $outputService->debug(sprintf('%s module is not enabled', Definitions::NAMESPACE));

            return self::FAILURE;
        }

        try {
            $snapshotManagement->sendAll();
            $snapshotManagement->resetAll();

            $outputService->debug('Successfully processed snapshots');
        } catch (Exception $exception) {
            if ($configManagement->shouldThrowOnError()) {
                throw $exception;
            }

            $outputService->debug($exception->getMessage(), ['Trace' => $exception->getTraceAsString()]);
        }

        return self::SUCCESS;
    }
}
