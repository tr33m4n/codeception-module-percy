<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exception\ConfigException;

class ConfigManagement
{
    public const PERCY_NODE_PATH = 'PERCY_NODE_PATH';

    public const PERCY_ENABLED = 'PERCY_ENABLED';

    public const PERCY_TOKEN = 'PERCY_TOKEN';

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
     * Whether Percy has been disabled in env config
     */
    public function isEnabled(): bool
    {
        $percyEnabled = getenv(self::PERCY_ENABLED);

        // `getenv` will return `false` if the env var is not set, string "1" or "0" otherwise
        return !is_string($percyEnabled) || filter_var($percyEnabled, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Whether the Percy token has been set
     */
    public function hasPercyToken(): bool
    {
        $percyToken = getenv(self::PERCY_TOKEN);

        return is_string($percyToken) && strlen($percyToken);
    }

    /**
     * Get Percy CLI browser JS path
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     */
    public function getPercyCliBrowserJsPath(): string
    {
        return realpath($this->validateFilePath(__DIR__ . '/../../../../node_modules/@percy/dom/dist/bundle.js')) ?: '';
    }

    /**
     * Get Percy CLI executable path
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     */
    public function getPercyCliExecutablePath(): string
    {
        return realpath($this->validateFilePath(__DIR__ . '/../../../../node_modules/.bin/percy')) ?: '';
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
     * If `PERCY_NODE_PATH` has been configured, use that as the path to the Node executable, rather than what's
     * configured in `PATH`
     *
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     */
    public function getNodePath(): string
    {
        $configuredNodePath = getenv(self::PERCY_NODE_PATH);
        if (is_string($configuredNodePath) && strlen($configuredNodePath)) {
            return realpath($this->validateFilePath($configuredNodePath)) ?: 'node';
        }

        return 'node';
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
     * Get snapshot folder
     */
    public function getSnapshotFolder(): ?string
    {
        $snapshotFolder = $this->get('snapshotFolder');
        if (!is_string($snapshotFolder)) {
            return null;
        }

        return $snapshotFolder;
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
     * Check if we should throw on error
     */
    public function shouldThrowOnError(): bool
    {
        return (bool) $this->get('throwOnError');
    }

    /**
     * Check if we should be collecting snapshots, rather than sending
     */
    public function shouldCollectOnly(): bool
    {
        return (bool) $this->get('collectOnly');
    }

    /**
     * Check whether we're in debug mode
     */
    public function isDebugMode(): bool
    {
        return (bool) $this->get('snapshotServerDebug');
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
