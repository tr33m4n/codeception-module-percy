<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exception\ConfigException;

class ConfigManagement
{
    /**
     * @var array<string, mixed>
     */
    private array $config;

    /**
     * ConfigManagement constructor.
     *
     * @param array<string, mixed> $config
     */
    public function __construct(
        array $config = []
    ) {
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
     * @return string
     */
    public function getPercyCliBrowserJsPath(): string
    {
        return $this->validateFilePath(__DIR__ . '/../../../../node_modules/@percy/dom/dist/bundle.js');
    }

    /**
     * Get Percy CLI executable path
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     * @return string
     */
    public function getPercyCliExecutablePath(): string
    {
        return $this->validateFilePath(__DIR__ . '/../../../../node_modules/.bin/percy');
    }

    /**
     * Get snapshot base URL
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     * @return string
     */
    public function getSnapshotBaseUrl(): string
    {
        /** @var string $snapshotBaseUrl */
        $snapshotBaseUrl = $this->get('snapshotBaseUrl');
        if (!filter_var($snapshotBaseUrl, FILTER_VALIDATE_URL)) {
            throw new ConfigException('Snapshot base URL is not a valid URL');
        }

        return $snapshotBaseUrl;
    }

    /**
     * Get snapshot path
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     * @return string
     */
    public function getSnapshotPath(): string
    {
        $snapshotPath = $this->get('snapshotPath');
        if (!is_string($snapshotPath)) {
            throw new ConfigException('Snapshot path is not a string');
        }

        return $snapshotPath;
    }

    /**
     * Get snapshot server timeout
     *
     * @return float|null
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
     * Get serialize config
     *
     * @throws \JsonException
     * @return string
     */
    public function getSerializeConfig(): string
    {
        return json_encode($this->get('serializeConfig'), JSON_THROW_ON_ERROR);
    }

    /**
     * Check if we should clean snapshot storage
     *
     * @return bool
     */
    public function shouldCleanSnapshotStorage(): bool
    {
        return (bool) $this->get('cleanSnapshotStorage');
    }

    /**
     * Check if we should throw on adapter error
     *
     * @return bool
     */
    public function shouldThrowOnAdapterError(): bool
    {
        return (bool) $this->get('throwOnAdapterError');
    }

    /**
     * Validate file path
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     * @param string $filePath
     * @return string
     */
    private function validateFilePath(string $filePath): string
    {
        if (!is_file($filePath)) {
            throw new ConfigException(sprintf('File "%s" does not exist!', $filePath));
        }

        return $filePath;
    }
}
