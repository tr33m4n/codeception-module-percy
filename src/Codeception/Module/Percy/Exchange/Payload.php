<?php

namespace Codeception\Module\Percy\Exchange;

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
    const NAME = 'name';

    /**
     * Url key
     */
    const URL = 'url';

    /**
     * Percy CSS key
     */
    const PERCY_CSS = 'percyCSS';

    /**
     * Min height key
     */
    const MIN_HEIGHT = 'minHeight';

    /**
     * DOM snapshot key
     */
    const DOM_SNAPSHOT = 'domSnapshot';

    /**
     * Client info key
     */
    const CLIENT_INFO = 'clientInfo';

    /**
     * Enable JavaScript key
     */
    const ENABLE_JAVASCRIPT = 'enableJavaScript';

    /**
     * Environment info key
     */
    const ENVIRONMENT_INFO = 'environmentInfo';

    /**
     * Widths key
     */
    const WIDTHS = 'widths';

    /**
     * Array of keys that can be set from config
     */
    const PUBLIC_KEYS = [
        self::PERCY_CSS,
        self::MIN_HEIGHT,
        self::ENABLE_JAVASCRIPT,
        self::WIDTHS
    ];

    /**
     * Array of keys that can't be set from config
     */
    const PRIVATE_KEYS = [
        self::NAME,
        self::URL,
        self::DOM_SNAPSHOT,
        self::CLIENT_INFO,
        self::ENVIRONMENT_INFO
    ];

    /**
     * @var array<string, mixed>
     */
    private $config = [];

    /**
     * From config
     *
     * @param array<string, mixed> $config
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public static function from(array $config) : Payload
    {
        return array_reduce(
            array_keys($config),
            function (Payload $payload, string $configKey) use ($config) {
                if (!in_array($configKey, self::PUBLIC_KEYS)) {
                    throw new InvalidArgumentException(
                        sprintf('The following key is not allowed to be set through config: %s', $configKey)
                    );
                }

                return self::withValue($payload, $configKey, $config[$configKey]);
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
    public function withName(string $name) : Payload
    {
        return self::withValue(clone $this, self::NAME, $name);
    }

    /**
     * With URL
     *
     * @param string $url
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public function withUrl(string $url) : Payload
    {
        return self::withValue(clone $this, self::URL, $url);
    }

    /**
     * With Percy CSS
     *
     * @param string|null $percyCss
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public function withPercyCss(?string $percyCss) : Payload
    {
        return self::withValue(clone $this, self::PERCY_CSS, $percyCss);
    }

    /**
     * With min height
     *
     * @param int|null $minHeight
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public function withMinHeight(?int $minHeight) : Payload
    {
        return self::withValue(clone $this, self::MIN_HEIGHT, $minHeight);
    }

    /**
     * With DOM snapshot
     *
     * @param string $domSnapshot
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public function withDomSnapshot(string $domSnapshot) : Payload
    {
        return self::withValue(clone $this, self::DOM_SNAPSHOT, $domSnapshot);
    }

    /**
     * With client info
     *
     * @param string $clientInfo
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public function withClientInfo(string $clientInfo) : Payload
    {
        return self::withValue(clone $this, self::CLIENT_INFO, $clientInfo);
    }

    /**
     * With enable JavaScript
     *
     * @param bool $enableJavaScript
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public function withEnableJavaScript(bool $enableJavaScript) : Payload
    {
        return self::withValue(clone $this, self::ENABLE_JAVASCRIPT, $enableJavaScript);
    }

    /**
     * With environment info
     *
     * @param string $environmentInfo
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public function withEnvironmentInfo(string $environmentInfo) : Payload
    {
        return self::withValue(clone $this, self::ENVIRONMENT_INFO, $environmentInfo);
    }

    /**
     * With widths
     *
     * @param int[] $widths
     * @return \Codeception\Module\Percy\Exchange\Payload
     */
    public function withWidths(array $widths) : Payload
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
    private static function withValue(Payload $payload, string $key, $value) : Payload
    {
        if (!in_array($key, array_merge(self::PUBLIC_KEYS, self::PRIVATE_KEYS))) {
            throw new InvalidArgumentException(sprintf('Invalid payload key %s', $key));
        }

        $payload->config[$key] = $value;

        return $payload;
    }

    /**
     * Encode config as JSON when casting to string
     *
     * @return string
     */
    public function __toString() : string
    {
        return json_encode($this->config) ?: '';
    }
}
