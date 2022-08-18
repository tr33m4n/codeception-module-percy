<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exception\ConfigException;

class ConfigManagement
{
    private Serializer $serializer;

    /**
     * @var array<string, mixed>
     */
    private array $config;

    /**
     * ConfigManagement constructor.
     *
     * @param array<string, mixed>                 $config
     */
    public function __construct(
        Serializer $serializer,
        array $config = []
    ) {
        $this->serializer = $serializer;
        $this->config = $config;
    }

    /**
     * Get config
     *
     * @param string|null $key
     * @return mixed|null
     */
    public function get(string $key = null)
    {
        if (!$key) {
            return $this->config;
        }

        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        return null;
    }

    /**
     * Get Percy CLI browser JS path
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     */
    public function getPercyCliBrowserJsPath(): string
    {
        return $this->validateFilePath(__DIR__ . '/../../../../node_modules/@percy/dom/dist/bundle.js');
    }

    /**
     * Get Percy CLI executable path
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     */
    public function getPercyCliExecutablePath(): string
    {
        return $this->validateFilePath(__DIR__ . '/../../../../node_modules/.bin/percy');
    }

    /**
     * Get Percy CLI browser JS
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     */
    public function getPercyCliBrowserJs(): string
    {
        $browserJs = file_get_contents($this->getPercyCliBrowserJsPath());
        if (!$browserJs) {
            throw new ConfigException('Unable to resolve browser JS');
        }

        return $browserJs;
    }

    /**
     * Get snapshot server timeout
     */
    public function getSnapshotServerTimeout(): ?float
    {
        $snapshotServerTimeout = $this->get('snapshotServerTimeout');
        if (!is_numeric($snapshotServerTimeout)) {
            return null;
        }

        return (float) $snapshotServerTimeout;
    }

    /**
     * Get snapshot server port
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     */
    public function getSnapshotServerPort(): int
    {
        /** @var int $snapshotServerPort */
        $snapshotServerPort = $this->get('snapshotServerPort');
        if (!is_int($snapshotServerPort)) {
            throw new ConfigException(sprintf('"%s" is an invalid port number', $snapshotServerPort));
        }

        return $snapshotServerPort;
    }

    /**
     * Get snapshot server URI
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     */
    public function getSnapshotServerUri(): string
    {
        $snapshotServerUri = sprintf('http://localhost:%s/percy/snapshot', $this->getSnapshotServerPort());
        if (!filter_var($snapshotServerUri, FILTER_VALIDATE_URL)) {
            throw new ConfigException(sprintf('Snapshot URI "%s" is not valid', $snapshotServerUri));
        }

        return $snapshotServerUri;
    }

    /**
     * Get snapshot config
     *
     * @return array<string, mixed>
     */
    public function getSnapshotConfig(): array
    {
        $snapshotConfig = $this->get('snapshotConfig');
        if (!is_array($snapshotConfig)) {
            return [];
        }

        return $snapshotConfig;
    }

    /**
     * Get instance ID
     */
    public function getInstanceId(): ?string
    {
        $instanceId = $this->get('instanceId');
        if (!is_string($instanceId)) {
            return null;
        }

        return $instanceId;
    }

    /**
     * Get serialize config
     *
     * @throws \JsonException
     */
    public function getSerializeConfig(): string
    {
        /** @var array<string, mixed> $serializedConfig */
        $serializedConfig = $this->get('serializeConfig');
        if (!is_array($serializedConfig)) {
            return '';
        }

        return $this->serializer->serialize($serializedConfig);
    }

    /**
     * Check if we should clean snapshot storage
     */
    public function shouldCleanSnapshotStorage(): bool
    {
        return (bool) $this->get('cleanSnapshotStorage');
    }

    /**
     * Check if we should throw on adapter error
     */
    public function shouldThrowOnAdapterError(): bool
    {
        return (bool) $this->get('throwOnAdapterError');
    }

    /**
     * Check if we should be collecting snapshots, rather than sending
     */
    public function shouldCollectOnly(): bool
    {
        return (bool) $this->get('collectOnly');
    }

    /**
     * Validate file path
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     */
    private function validateFilePath(string $filePath): string
    {
        if (!is_file($filePath)) {
            throw new ConfigException(sprintf('File "%s" does not exist!', $filePath));
        }

        return $filePath;
    }
}
