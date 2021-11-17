<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Discovery;

use Codeception\Module\Percy\Exchange\Action\Request\Snapshot;
use Codeception\Module\WebDriver;
use Facebook\WebDriver\Chrome\ChromeDevToolsDriver;

class DiscoverResources
{
    private $webDriver;

    private $resourceFactory;

    public function __construct(
        WebDriver $webDriver,
        ResourceFactory $resourceFactory
    ) {
        $this->webDriver = $webDriver;
        $this->resourceFactory = $resourceFactory;
    }

    public function execute(Snapshot $snapshot)
    {
        $resources = ResourceCollection::create([
            $this->resourceFactory->createRootResource($snapshot->getUrl(), $snapshot->getDomSnapshot())
        ]);

        // Create new page
        $devTools = new ChromeDevToolsDriver($this->webDriver->webDriver);
        [$targetId] = $devTools->execute('Target.createTarget', ['url' => '']);
        [$sessionId] = $devTools->execute('Target.attachToTarget', ['targetId' => $targetId, 'flatten' => true]);


        $devTools->execute('Fetch.enable', ['patterns' => [$snapshot->getUrl()->getHost()]]); // Enable network interception

        $this->webDriver->amOnUrl((string) $snapshot->getUrl());
    }
}
