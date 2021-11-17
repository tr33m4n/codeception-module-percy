<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Action\Request;

use Codeception\Module\Percy\Persistence\Dom;
use League\Uri\Uri;
use League\Uri\Contracts\UriInterface;
use JsonSerializable;

class Snapshot implements JsonSerializable
{
    public const URL_KEY = 'url';

    public const NAME_KEY = 'name';

    public const PERCY_CSS_KEY = 'percyCSS';

    public const MIN_HEIGHT_KEY = 'minHeight';

    public const DOM_SNAPSHOT_KEY = 'domSnapshot';

    public const CLIENT_INFO_KEY = 'clientInfo';

    public const ENVIRONMENT_INFO_KEY = 'environmentInfo';

    public const ENABLE_JAVASCRIPT_KEY = 'enableJavaScript';

    public const WIDTHS_KEY = 'widths';

    /**
     * Array of keys that can be set from config
     */
    public const FROM_CONFIG = [
        self::PERCY_CSS_KEY,
        self::MIN_HEIGHT_KEY,
        self::ENABLE_JAVASCRIPT_KEY,
        self::WIDTHS_KEY
    ];

    /**
     * @var \League\Uri\Contracts\UriInterface
     */
    private $url;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $percyCss;

    /**
     * @var int|null
     */
    private $minHeight;

    /**
     * @var \Codeception\Module\Percy\Persistence\Dom
     */
    private $domSnapshot;

    /**
     * @var string|null
     */
    private $clientInfo;

    /**
     * @var string|null
     */
    private $environmentInfo;

    /**
     * @var bool
     */
    private $enabledJavaScript = false;

    /**
     * @var int[]
     */
    private $widths = [];

    /**
     * Snapshot constructor.
     */
    private function __construct()
    {
        //
    }

    /**
     * From config array
     *
     * @param array<string, mixed> $configArray
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public static function from(array $configArray): Snapshot
    {
        return array_reduce(
            array_keys($configArray),
            static function (Snapshot $snapshot, string $configKey) use ($configArray): Snapshot {
                if (!in_array($configKey, self::FROM_CONFIG)) {
                    return $snapshot;
                }

                return $snapshot->{'with' . ucfirst($configKey)}($configArray[$configKey]);
            },
            new self()
        );
    }

    /**
     * With URL
     *
     * @param string $url
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public function withUrl(string $url): Snapshot
    {
        $snapshot = clone $this;
        $snapshot->url = Uri::createFromString($url);

        return $snapshot;
    }

    /**
     * With name
     *
     * @param string $name
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public function withName(string $name): Snapshot
    {
        $snapshot = clone $this;
        $snapshot->name = $name;

        return $snapshot;
    }

    /**
     * With Percy CSS
     *
     * @param string $percyCss
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public function withPercyCss(string $percyCss): Snapshot
    {
        $snapshot = clone $this;
        $snapshot->percyCss = $percyCss;

        return $snapshot;
    }

    /**
     * With min height
     *
     * @param int $minHeight
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public function withMinHeight(int $minHeight): Snapshot
    {
        $snapshot = clone $this;
        $snapshot->minHeight = $minHeight;

        return $snapshot;
    }

    /**
     * With DOM snapshot
     *
     * @param \Codeception\Module\Percy\Persistence\Dom $domSnapshot
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public function withDomSnapshot(Dom $domSnapshot): Snapshot
    {
        $snapshot = clone $this;
        $snapshot->domSnapshot = $domSnapshot;

        return $snapshot;
    }

    /**
     * With client info
     *
     * @param string $clientInfo
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public function withClientInfo(string $clientInfo): Snapshot
    {
        $snapshot = clone $this;
        $snapshot->clientInfo = $clientInfo;

        return $snapshot;
    }

    /**
     * With environment info
     *
     * @param string $environmentInfo
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public function withEnvironmentInfo(string $environmentInfo): Snapshot
    {
        $snapshot = clone $this;
        $snapshot->environmentInfo = $environmentInfo;

        return $snapshot;
    }

    /**
     * With enable JavaScript
     *
     * @param bool $enableJavaScript
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public function withEnableJavaScript(bool $enableJavaScript): Snapshot
    {
        $snapshot = clone $this;
        $snapshot->enabledJavaScript = $enableJavaScript;

        return $snapshot;
    }

    /**
     * With widths
     *
     * @param int[] $widths
     * @return \Codeception\Module\Percy\Exchange\Action\Request\Snapshot
     */
    public function withWidths(array $widths): Snapshot
    {
        $snapshot = clone $this;
        $snapshot->widths = $widths;

        return $snapshot;
    }

    /**
     * Get URL
     *
     * @return \League\Uri\Contracts\UriInterface
     */
    public function getUrl(): UriInterface
    {
        return $this->url;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        $snapshotUrl = $this->getUrl();

        return $this->name ?? "{$snapshotUrl->getPath()}{$snapshotUrl->getQuery()}{$snapshotUrl->getFragment()}";
    }

    /**
     * Get Percy CSS
     *
     * @return string|null
     */
    public function getPercyCss(): ?string
    {
        return $this->percyCss;
    }

    /**
     * Get min height
     *
     * @return int|null
     */
    public function getMinHeight(): ?int
    {
        return $this->minHeight;
    }

    /**
     * Get DOM snapshot
     *
     * @return \Codeception\Module\Percy\Persistence\Dom
     */
    public function getDomSnapshot(): Dom
    {
        return $this->domSnapshot;
    }

    /**
     * Get client info
     *
     * @return string|null
     */
    public function getClientInfo(): ?string
    {
        return $this->clientInfo;
    }

    /**
     * Get environment info
     *
     * @return string|null
     */
    public function getEnvironmentInfo(): ?string
    {
        return $this->environmentInfo;
    }

    /**
     * Get enable Javascript
     *
     * @return bool
     */
    public function getEnableJavascript(): bool
    {
        return $this->enabledJavaScript;
    }

    /**
     * Get widths
     *
     * @return int[]
     */
    public function getWidths(): array
    {
        return $this->widths;
    }

    /**
     * As array
     *
     * @return array<string, mixed>
     */
    public function asArray(): array
    {
        return [
            self::URL_KEY => $this->getUrl(),
            self::NAME_KEY => $this->getName(),
            self::PERCY_CSS_KEY => $this->getPercyCss(),
            self::MIN_HEIGHT_KEY => $this->getMinHeight(),
            self::DOM_SNAPSHOT_KEY => $this->getDomSnapshot(),
            self::CLIENT_INFO_KEY => $this->getClientInfo(),
            self::ENVIRONMENT_INFO_KEY => $this->getEnvironmentInfo(),
            self::ENABLE_JAVASCRIPT_KEY => $this->getEnableJavascript(),
            self::WIDTHS_KEY => $this->getWidths()
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
        return json_encode($this->asArray(), JSON_THROW_ON_ERROR) ?: '';
    }

    /**
     * {@inheritdoc}
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->asArray();
    }
}
