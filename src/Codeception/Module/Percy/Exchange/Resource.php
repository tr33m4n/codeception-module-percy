<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange;

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
     * @var string|null
     */
    private $url;

    /**
     * @var string|null
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
     * From resource data
     *
     * @param array<string, string> $resourceData
     * @return \Codeception\Module\Percy\Exchange\Resource
     */
    public static function from(array $resourceData = []): Resource
    {
        $resource = new self();
        $resource->sha = $resourceData[self::SHA_FIELD] ?? null;
        $resource->url = $resourceData[self::URL_FIELD] ?? null;
        $resource->root = $resourceData[self::ROOT_FIELD] ?? null;
        $resource->mimeType = $resourceData[self::MIME_TYPE_FIELD] ?? null;
        $resource->content = $resourceData[self::CONTENT_FIELD] ?? null;

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
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Get root
     *
     * @return string|null
     */
    public function getRoot(): ?string
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
