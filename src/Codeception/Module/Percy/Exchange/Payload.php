<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

/**
 * Class Payload
 *
 * @package Codeception\Module\Percy\Exchange
 */
class Payload
{
    /**
     * Name key
     */
    public const NAME = 'name';

    /**
     * Url key
     */
    public const URL = 'url';

    /**
     * Percy CSS key
     */
    public const PERCY_CSS = 'percyCSS';

    /**
     * Min height key
     */
    public const MIN_HEIGHT = 'minHeight';

    /**
     * DOM snapshot key
     */
    public const DOM_SNAPSHOT = 'domSnapshot';

    /**
     * Client info key
     */
    public const CLIENT_INFO = 'clientInfo';

    /**
     * Enable JavaScript key
     */
    public const ENABLE_JAVASCRIPT = 'enableJavaScript';

    /**
     * Environment info key
     */
    public const ENVIRONMENT_INFO = 'environmentInfo';

    /**
     * Widths key
     */
    public const WIDTHS = 'widths';

    /**
     * @var array<string, mixed>
     */
    private $config = [];

    /**
     * Payload constructor.
     */
    private function __construct()
    {
        //
    }

    /**
     * From config
     *
     * @param array<string, mixed> $publicConfig
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public static function from(array $publicConfig): Payload
    {
        return array_reduce(
            array_keys($publicConfig),
            function (Payload $payload, string $configKey) use ($publicConfig) {
                ValidatePublicConfig::execute($configKey);

                return self::withValue($payload, $configKey, $publicConfig[$configKey]);
            },
            new self()
        );
    }

    /**
     * With name
     *
     * @param string $name
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public function withName(string $name): Payload
    {
        return self::withValue(clone $this, self::NAME, $name);
    }

    /**
     * With URL
     *
     * @param string $url
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public function withUrl(string $url): Payload
    {
        return self::withValue(clone $this, self::URL, $url);
    }

    /**
     * With Percy CSS
     *
     * @param string|null $percyCss
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public function withPercyCss(?string $percyCss): Payload
    {
        return self::withValue(clone $this, self::PERCY_CSS, $percyCss);
    }

    /**
     * With min height
     *
     * @param int|null $minHeight
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public function withMinHeight(?int $minHeight): Payload
    {
        return self::withValue(clone $this, self::MIN_HEIGHT, $minHeight);
    }

    /**
     * With DOM snapshot
     *
     * @throws \Codeception\Module\Percy\Exception\StorageException
     * @param string $domSnapshot
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public function withDomSnapshot(string $domSnapshot): Payload
    {
        return self::withValue(clone $this, self::DOM_SNAPSHOT, SnapshotStorage::save($domSnapshot));
    }

    /**
     * With client info
     *
     * @param string $clientInfo
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public function withClientInfo(string $clientInfo): Payload
    {
        return self::withValue(clone $this, self::CLIENT_INFO, $clientInfo);
    }

    /**
     * With enable JavaScript
     *
     * @param bool $enableJavaScript
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public function withEnableJavaScript(bool $enableJavaScript): Payload
    {
        return self::withValue(clone $this, self::ENABLE_JAVASCRIPT, $enableJavaScript);
    }

    /**
     * With environment info
     *
     * @param string $environmentInfo
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public function withEnvironmentInfo(string $environmentInfo): Payload
    {
        return self::withValue(clone $this, self::ENVIRONMENT_INFO, $environmentInfo);
    }

    /**
     * With widths
     *
     * @param int[] $widths
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public function withWidths(array $widths): Payload
    {
        return self::withValue(clone $this, self::WIDTHS, $widths);
    }

    /**
     * With value
     *
     * @throws \InvalidArgumentException
     * @param \Codeception\Module\Percy\Exchange\Payload $payload
     * @param string                                     $key
     * @param mixed                                      $value
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    private static function withValue(Payload $payload, string $key, $value): Payload
    {
        $payload->config[$key] = $value;

        return $payload;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->config[self::NAME] ?? '';
    }

    /**
     * Get DOM snapshot
     *
     * @return \Codeception\Module\Percy\Exchange\Snapshot|null
     */
    public function getDomSnapshot(): ?Snapshot
    {
        return $this->config[self::DOM_SNAPSHOT] ?? null;
    }

    /**
     * Encode config as JSON when casting to string
     *
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->config) ?: '';
    }
}
