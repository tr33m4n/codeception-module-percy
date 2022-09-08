<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use InvalidArgumentException;
use JsonSerializable;

class Snapshot implements JsonSerializable
{
    public const NAME = 'name';

    public const URL = 'url';

    public const PERCY_CSS = 'percyCSS';

    public const MIN_HEIGHT = 'minHeight';

    public const DOM_SNAPSHOT = 'domSnapshot';

    public const CLIENT_INFO = 'clientInfo';

    public const ENABLE_JAVASCRIPT = 'enableJavaScript';

    public const ENVIRONMENT_INFO = 'environmentInfo';

    public const WIDTHS = 'widths';

    /**
     * Array of keys that are optional
     */
    public const OPTIONAL_KEYS = [
        self::PERCY_CSS,
        self::MIN_HEIGHT,
        self::ENABLE_JAVASCRIPT,
        self::WIDTHS
    ];

    /**
     * Array of required keys
     */
    public const REQUIRED_KEYS = [
        self::DOM_SNAPSHOT,
        self::NAME,
        self::URL,
        self::CLIENT_INFO,
        self::ENVIRONMENT_INFO
    ];

    private string $name;

    private string $domSnapshot;

    private string $url;

    private string $clientInfo;

    private string $environmentInfo;

    /**
     * @var array<string, mixed>
     */
    private array $config = [];

    /**
     * Snapshot constructor.
     */
    private function __construct()
    {
        //
    }

    /**
     * Create from file path
     *
     * @param array<string, mixed> $additionalConfig
     */
    public static function create(
        string $domSnapshot,
        string $name,
        string $url,
        string $clientInfo,
        string $environmentInfo,
        array $additionalConfig = []
    ): Snapshot {
        $snapshot = new self();
        $snapshot->domSnapshot = $domSnapshot;
        $snapshot->name = $name;
        $snapshot->url = $url;
        $snapshot->clientInfo = $clientInfo;
        $snapshot->environmentInfo = $environmentInfo;

        return array_reduce(
            array_keys($additionalConfig),
            static function (Snapshot $snapshot, string $configKey) use ($additionalConfig): Snapshot {
                if (!in_array($configKey, self::OPTIONAL_KEYS)) {
                    throw new InvalidArgumentException(
                        sprintf('"%s" cannot be set through config', $configKey)
                    );
                }

                return $snapshot->withConfigValue($configKey, $additionalConfig[$configKey]);
            },
            $snapshot
        );
    }

    /**
     * Hydrate snapshot from snapshot data
     *
     * @param array<string, string> $snapshotData
     */
    public static function hydrate(array $snapshotData): Snapshot
    {
        if (count(array_intersect_key($snapshotData, array_flip(self::REQUIRED_KEYS))) !== count(self::REQUIRED_KEYS)) {
            throw new InvalidArgumentException('Missing required snapshot fields');
        }

        return self::create(
            $snapshotData[self::DOM_SNAPSHOT],
            $snapshotData[self::NAME],
            $snapshotData[self::URL],
            $snapshotData[self::CLIENT_INFO],
            $snapshotData[self::ENVIRONMENT_INFO],
            array_diff_key($snapshotData, array_flip(self::REQUIRED_KEYS))
        );
    }

    /**
     * With value
     *
     * @param mixed  $value
     */
    public function withConfigValue(string $key, $value): Snapshot
    {
        $snapshot = clone $this;
        $snapshot->config[$key] = $value;

        return $snapshot;
    }

    /**
     * Get name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get URL
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Get client info
     */
    public function getClientInfo(): string
    {
        return $this->clientInfo;
    }

    /**
     * Get environment info
     */
    public function getEnvironmentInfo(): string
    {
        return $this->environmentInfo;
    }

    /**
     * Get DOM snapshot
     */
    public function getDomSnapshot(): string
    {
        return $this->domSnapshot;
    }

    /**
     * To array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_merge(
            [
                self::NAME => $this->getName(),
                self::URL => $this->getUrl(),
                self::CLIENT_INFO => $this->getClientInfo(),
                self::ENVIRONMENT_INFO => $this->getEnvironmentInfo(),
                self::DOM_SNAPSHOT => $this->getDomSnapshot()
            ],
            $this->config
        );
    }

    /**
     * {@inheritdoc}
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
