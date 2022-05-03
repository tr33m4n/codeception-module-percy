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
     * Array of keys that can be set from config
     */
    public const PUBLIC_KEYS = [
        self::PERCY_CSS,
        self::MIN_HEIGHT,
        self::ENABLE_JAVASCRIPT,
        self::WIDTHS
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
     * @param string               $domSnapshot
     * @param string               $name
     * @param string               $url
     * @param string               $clientInfo
     * @param string               $environmentInfo
     * @param array<string, mixed> $additionalConfig
     * @return \Codeception\Module\Percy\Snapshot
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
                if (!in_array($configKey, self::PUBLIC_KEYS)) {
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
     * With value
     *
     * @param string $key
     * @param mixed  $value
     * @return \Codeception\Module\Percy\Snapshot
     */
    public function withConfigValue(string $key, $value): Snapshot
    {
        $snapshot = clone $this;
        $snapshot->config[$key] = $value;

        return $snapshot;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get URL
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Get client info
     *
     * @return string
     */
    public function getClientInfo(): string
    {
        return $this->clientInfo;
    }

    /**
     * Get environment info
     *
     * @return string
     */
    public function getEnvironmentInfo(): string
    {
        return $this->environmentInfo;
    }

    /**
     * Get DOM snapshot
     *
     * @return string
     */
    public function getDomSnapshot(): string
    {
        return $this->domSnapshot;
    }

    /**
     * {@inheritdoc}
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
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
}
