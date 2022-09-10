<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Command;

use Codeception\Configuration;
use Codeception\CustomCommandInterface;
use Codeception\Lib\Console\Output;
use Codeception\Module\Percy\ConfigManagement;
use Codeception\Module\Percy\Definitions;
use Codeception\Module\Percy\Exception\PercyDisabledException;
use Codeception\Module\Percy\Output as PercyOutput;
use Codeception\Module\Percy\ServiceContainer;
use Codeception\Util\Debug;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class ProcessSnapshots extends Command implements CustomCommandInterface
{
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
     * @throws \Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $globalConfig = Configuration::config();
        /** @var string $suite */
        $suite = $input->getArgument(self::SUITE_ARGUMENT);
        $modulesConfig = Configuration::suiteSettings($suite, $globalConfig)['modules'] ?? [];
        // Codeception uses its own "output" when configuring the `debug` methods. Create a new instance
        Debug::setOutput(new Output($globalConfig));

        $serviceContainer = new ServiceContainer(
            null,
            array_merge(Definitions::DEFAULT_CONFIG, $modulesConfig['config'][Definitions::NAMESPACE] ?? [])
        );

        $snapshotManagement = $serviceContainer->getSnapshotManagement();
        $configManagement = $serviceContainer->getConfigManagement();
        $outputService = $serviceContainer->getOutput();
        $validateEnvironment = $serviceContainer->getValidateEnvironment();

        if (!in_array(Definitions::NAMESPACE, $modulesConfig['enabled'] ?? [])) {
            $outputService->debug(sprintf('%s module is not enabled', Definitions::NAMESPACE));

            return self::FAILURE;
        }

        try {
            $validateEnvironment->execute();

            $snapshotManagement->sendAll();
            $snapshotManagement->resetAll();

            $outputService->debug('Successfully processed snapshots');
        } catch (Throwable $exception) {
            return $this->handleException($exception, $outputService, $configManagement);
        }

        return self::SUCCESS;
    }

    /**
     * Handle exception
     *
     * @throws \Throwable
     */
    private function handleException(
        Throwable $exception,
        PercyOutput $outputService,
        ConfigManagement $configManagement
    ): int {
        // Always error silently if it's a "Percy disabled" exception
        if ($exception instanceof PercyDisabledException) {
            $outputService->debug($exception);

            return self::SUCCESS;
        }

        if ($configManagement->shouldThrowOnError()) {
            throw $exception;
        }

        $outputService->debug($exception->getMessage(), ['Trace' => $exception->getTraceAsString()]);

        return self::SUCCESS;
    }
}
