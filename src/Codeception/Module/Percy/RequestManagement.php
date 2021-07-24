<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exchange\ClientFactory;
use Codeception\Module\Percy\Exchange\Payload;

/**
 * Class RequestManagement
 *
 * @package Codeception\Module\Percy
 */
class RequestManagement
{
    /**
     * @var \Codeception\Module\Percy\Exchange\Payload[]
     */
    private static $payloads = [];

    /**
     * Add a payload
     *
     * @param \Codeception\Module\Percy\Exchange\Payload $payload
     */
    public static function addPayload(Payload $payload): void
    {
        self::$payloads[] = $payload;
    }

    /**
     * Check if request has payloads
     *
     * @return bool
     */
    public static function hasPayloads(): bool
    {
        return self::$payloads !== [];
    }

    /**
     * Send payloads to Percy
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     */
    public static function sendRequest(): void
    {
        if (!self::hasPayloads()) {
            return;
        }

        ProcessManagement::startPercyAgent();
        $client = ClientFactory::create();

        foreach (self::$payloads as $payload) {
            codecept_debug(sprintf('[Percy] Sending snapshot "%s"', $payload->getName()));

            $client->post(ConfigProvider::get('agentSnapshotPath'), $payload);
        }

        ProcessManagement::stopPercyAgent();

        self::resetRequest();
    }

    /**
     * Reset payloads
     */
    public static function resetRequest(): void
    {
        self::$payloads = [];

        if (ConfigProvider::get('cleanSnapshotStorage')) {
            SnapshotManagement::clean();
        }
    }
}
