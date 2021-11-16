<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\SnapshotManagement;
use InvalidArgumentException;

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
     * Array of keys that can be set from config
     */
    public const PUBLIC_KEYS = [
        self::PERCY_CSS,
        self::MIN_HEIGHT,
        self::ENABLE_JAVASCRIPT,
        self::WIDTHS
    ];

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
     * From array
     *
     * @param array<string, mixed> $payloadArray
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public static function from(array $payloadArray): Payload
    {
        return array_reduce(
            array_keys($payloadArray),
            static function (Payload $payload, string $configKey) use ($payloadArray): Payload {
                if (!in_array($configKey, self::PUBLIC_KEYS)) {
                    throw new InvalidArgumentException(
                        sprintf('"%s" cannot be set through config', $configKey)
                    );
                }

                return self::withValue($payload, $configKey, $payloadArray[$configKey]);
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
        return self::withValue(clone $this, self::DOM_SNAPSHOT, SnapshotManagement::save($domSnapshot));
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
     * Encode config as JSON when casting to string
     *
     * @throws \JsonException
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->config, JSON_THROW_ON_ERROR) ?: '';
    }
}
