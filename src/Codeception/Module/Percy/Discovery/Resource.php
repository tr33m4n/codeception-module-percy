<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Discovery;

use League\Uri\Contracts\UriInterface;
use Codeception\Module\Percy\Exception\DiscoveryException;

class Resource
{
    public const SHA_FIELD = 'sha';

    public const URL_FIELD = 'url';

    public const ROOT_FIELD = 'root';

    public const MIME_TYPE_FIELD = 'mime_type';

    public const CONTENT_FIELD = 'content';

    /**
     * @var string|null
     */
    private $sha;

    /**
     * @var \League\Uri\Contracts\UriInterface|null
     */
    private $url;

    /**
     * @var bool|null
     */
    private $root;

    /**
     * @var string|null
     */
    private $mimeType;

    /**
     * @var string|null
     */
    private $content;

    /**
     * Resource constructor.
     */
    private function __construct()
    {
        //
    }

    /**
     * Create new instance
     *
     * @return \Codeception\Module\Percy\Discovery\Resource
     */
    public static function create(): Resource
    {
        return new self();
    }

    /**
     * Hydrate from array
     *
     * @throws \Codeception\Module\Percy\Exception\DiscoveryException
     * @param array<string, mixed> $resourceData
     * @return \Codeception\Module\Percy\Discovery\Resource
     */
    public static function hydrate(array $resourceData = []): Resource
    {
        if (!array_key_exists(self::URL_FIELD, $resourceData)
            || !$resourceData[self::URL_FIELD] instanceof UriInterface
            || !array_key_exists(self::CONTENT_FIELD, $resourceData)
        ) {
            throw new DiscoveryException('Resource data is invalid!');
        }

        return self::create()
            ->withUrl($resourceData[self::URL_FIELD])
            ->withRoot($resourceData[self::ROOT_FIELD] ?? false)
            ->withMimeType($resourceData[self::MIME_TYPE_FIELD] ?? null)
            ->withContent($resourceData[self::CONTENT_FIELD]);
    }

    /**
     * With URL
     *
     * @param \League\Uri\Contracts\UriInterface $url
     * @return \Codeception\Module\Percy\Discovery\Resource
     */
    public function withUrl(UriInterface $url): Resource
    {
        $resource = clone $this;
        $resource->url = $url;

        return $resource;
    }

    /**
     * With root
     *
     * @param bool $root
     * @return \Codeception\Module\Percy\Discovery\Resource
     */
    public function withRoot(bool $root): Resource
    {
        $resource = clone $this;
        $resource->root = $root;

        return $resource;
    }

    /**
     * With MIME type
     *
     * @param string|null $mimeType
     * @return \Codeception\Module\Percy\Discovery\Resource
     */
    public function withMimeType(?string $mimeType): Resource
    {
        $resource = clone $this;
        $resource->mimeType = $mimeType;

        return $resource;
    }

    /**
     * With content
     *
     * @param string $content
     * @return \Codeception\Module\Percy\Discovery\Resource
     */
    public function withContent(string $content): Resource
    {
        $resource = clone $this;
        $resource->content = $content;
        $resource->sha = hash('sha256', $content);

        return $resource;
    }

    /**
     * Get SHA
     *
     * @return string|null
     */
    public function getSha(): ?string
    {
        return $this->sha;
    }

    /**
     * Get URL
     *
     * @return \League\Uri\Contracts\UriInterface|null
     */
    public function getUrl(): ?UriInterface
    {
        return $this->url;
    }

    /**
     * Get root
     *
     * @return bool|null
     */
    public function getRoot(): ?bool
    {
        return $this->root;
    }

    /**
     * Get MIME type
     *
     * @return string|null
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * Get content
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }
}
