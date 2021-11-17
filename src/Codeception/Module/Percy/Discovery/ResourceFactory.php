<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Discovery;

use League\Uri\Contracts\UriInterface;
use Codeception\Module\Percy\Persistence\Dom;

class ResourceFactory
{
    /**
     * Create root resource
     *
     * @param \League\Uri\Contracts\UriInterface $url
     * @param \Codeception\Module\Percy\Persistence\Dom $content
     * @return \Codeception\Module\Percy\Discovery\Resource
     */
    public function createRootResource(UriInterface $url, Dom $content): Resource
    {
        return Resource::create()
            ->withUrl($url->withFragment(null))
            ->withRoot(true)
            ->withMimeType('text/html')
            ->withContent((string) $content);
    }
}
