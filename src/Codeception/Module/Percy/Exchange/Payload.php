<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

use Codeception\Module\Percy\Snapshot;
use InvalidArgumentException;

class Payload
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

    /**
     * @var array<string, mixed>
     */
    private array $config = [];

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
     * @param \Codeception\Module\Percy\Snapshot $domSnapshot
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public function withDomSnapshot(Snapshot $domSnapshot): Payload
    {
        return self::withValue(clone $this, self::DOM_SNAPSHOT, $domSnapshot);
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
        if (!array_key_exists(self::NAME, $this->config)) {
            return '';
        }

        if (!is_string($this->config[self::NAME])) {
            return '';
        }

        return $this->config[self::NAME];
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
