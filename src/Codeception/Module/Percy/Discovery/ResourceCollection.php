<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Discovery;

class ResourceCollection
{
    /**
     * @var \Codeception\Module\Percy\Discovery\Resource[]
     */
    private $resources;

    /**
     * ResourceCollection constructor.
     *
     * @param \Codeception\Module\Percy\Discovery\Resource[] $resources
     */
    public function __construct(
        array $resources = []
    ) {
        $this->resources = $resources;
    }

    /**
     * Create resource collection
     *
     * @param \Codeception\Module\Percy\Discovery\Resource[] $resources
     * @return \Codeception\Module\Percy\Discovery\ResourceCollection
     */
    public static function create(array $resources = []): ResourceCollection
    {
        return new self($resources);
    }

    /**
     * Add resource
     *
     * @param \Codeception\Module\Percy\Discovery\Resource $resource
     * @return \Codeception\Module\Percy\Discovery\ResourceCollection
     */
    public function add(Resource $resource): ResourceCollection
    {
        $this->resources[(string) $resource->getUrl()] = $resource;

        return $this;
    }

    /**
     * Get resource
     *
     * @param string $url
     * @return \Codeception\Module\Percy\Discovery\Resource|null
     */
    public function get(string $url): ?Resource
    {
        return $this->resources[$url] ?? null;
    }
}
