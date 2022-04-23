<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exception\ConfigException;

class ConfigProvider
{
    /**
     * @var array<string, mixed>
     */
    private static array $config = [];

    /**
     * Hydrate config
     *
     * @param array<string, mixed> $config
     */
    public static function hydrate(array $config): void
    {
        self::$config = $config;
    }

    /**
     * Get config
     *
     * @param string|null $key
     * @return mixed|null
     */
    public static function get(string $key = null)
    {
        if (!$key) {
            return self::$config;
        }

        if (isset(self::$config[$key])) {
            return self::$config[$key];
        }

        return null;
    }

    /**
     * Get Percy CLI browser JS path
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     * @return string
     */
    public static function getPercyCliBrowserJsPath(): string
    {
        return self::validateFilePath(__DIR__ . '/../../../../node_modules/@percy/dom/dist/bundle.js');
    }

    /**
     * Get Percy CLI executable path
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     * @return string
     */
    public static function getPercyCliExecutablePath(): string
    {
        return self::validateFilePath(__DIR__ . '/../../../../node_modules/.bin/percy');
    }

    /**
     * Get snapshot base URL
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     * @return string
     */
    public static function getSnapshotBaseUrl(): string
    {
        /** @var string $snapshotBaseUrl */
        $snapshotBaseUrl = self::get('snapshotBaseUrl');
        if (!filter_var($snapshotBaseUrl, FILTER_VALIDATE_URL)) {
            throw new ConfigException('Snapshot base URL is not a valid URL');
        }

        return $snapshotBaseUrl;
    }

    /**
     * Get snapshot server timeout
     *
     * @return float|null
     */
    public static function getSnapshotServerTimeout(): ?float
    {
        $snapshotServerTimeout = self::get('snapshotServerTimeout');
        if (!is_numeric($snapshotServerTimeout)) {
            return null;
        }

        return (float) $snapshotServerTimeout;
    }

    /**
     * Check if we should clean snapshot storage
     *
     * @return bool
     */
    public static function shouldCleanSnapshotStorage(): bool
    {
        return (bool) ConfigProvider::get('cleanSnapshotStorage');
    }

    /**
     * Validate file path
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     * @param string $filePath
     * @return string
     */
    private static function validateFilePath(string $filePath): string
    {
        if (!is_file($filePath)) {
            throw new ConfigException(sprintf('File "%s" does not exist!', $filePath));
        }

        return $filePath;
    }
}
