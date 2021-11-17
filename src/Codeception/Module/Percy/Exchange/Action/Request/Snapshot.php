<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Action\Request;

use Codeception\Module\Percy\Persistence\Dom;
use InvalidArgumentException;

class Snapshot
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
     * Snapshot constructor.
     */
    private function __construct()
    {
        //
    }

    /**
     * From array
     *
     * @param array<string, mixed> $payloadArray
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public static function from(array $payloadArray): Snapshot
    {
        return array_reduce(
            array_keys($payloadArray),
            static function (Snapshot $payload, string $configKey) use ($payloadArray): Snapshot {
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
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public function withName(string $name): Snapshot
    {
        return self::withValue(clone $this, self::NAME, $name);
    }

    /**
     * With URL
     *
     * @param string $url
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public function withUrl(string $url): Snapshot
    {
        return self::withValue(clone $this, self::URL, $url);
    }

    /**
     * With Percy CSS
     *
     * @param string|null $percyCss
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public function withPercyCss(?string $percyCss): Snapshot
    {
        return self::withValue(clone $this, self::PERCY_CSS, $percyCss);
    }

    /**
     * With min height
     *
     * @param int|null $minHeight
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public function withMinHeight(?int $minHeight): Snapshot
    {
        return self::withValue(clone $this, self::MIN_HEIGHT, $minHeight);
    }

    /**
     * With DOM snapshot
     *
     * @param \Codeception\Module\Percy\Persistence\Dom $domSnapshot
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public function withDomSnapshot(Dom $domSnapshot): Snapshot
    {
        return self::withValue(clone $this, self::DOM_SNAPSHOT, $domSnapshot);
    }

    /**
     * With client info
     *
     * @param string $clientInfo
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public function withClientInfo(string $clientInfo): Snapshot
    {
        return self::withValue(clone $this, self::CLIENT_INFO, $clientInfo);
    }

    /**
     * With enable JavaScript
     *
     * @param bool $enableJavaScript
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public function withEnableJavaScript(bool $enableJavaScript): Snapshot
    {
        return self::withValue(clone $this, self::ENABLE_JAVASCRIPT, $enableJavaScript);
    }

    /**
     * With environment info
     *
     * @param string $environmentInfo
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public function withEnvironmentInfo(string $environmentInfo): Snapshot
    {
        return self::withValue(clone $this, self::ENVIRONMENT_INFO, $environmentInfo);
    }

    /**
     * With widths
     *
     * @param int[] $widths
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public function withWidths(array $widths): Snapshot
    {
        return self::withValue(clone $this, self::WIDTHS, $widths);
    }

    /**
     * With value
     *
     * @throws \InvalidArgumentException
     * @param \Codeception\Module\Percy\Exchange\Action\Request\Snapshot $payload
     * @param string                                                     $key
     * @param mixed                                                      $value
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    private static function withValue(Snapshot $payload, string $key, $value): Snapshot
    {
        $payload->config[$key] = $value;

        return $payload;
    }

    /**
     * As attributes array
     *
     * @return array<string, mixed>
     */
    public function asAttributesArray(): array
    {
        return [
            self::NAME => $this->config[self::NAME] ?? null,
            self::WIDTHS => $this->config[self::WIDTHS] ?? null,
            self::MIN_HEIGHT => $this->config[self::MIN_HEIGHT] ?? null,
            self::ENABLE_JAVASCRIPT => $this->config[self::ENABLE_JAVASCRIPT] ?? null
        ];
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
