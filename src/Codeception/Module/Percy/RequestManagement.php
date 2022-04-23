<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exception\ConfigException;
use Codeception\Module\Percy\Exchange\ClientFactory;
use Codeception\Module\Percy\Exchange\Payload;

class RequestManagement
{
    /**
     * @var \Codeception\Module\Percy\Exchange\Payload[]
     */
    private static array $payloads = [];

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
     * @throws \Codeception\Module\Percy\Exception\ConfigException
     */
    public static function sendRequest(): void
    {
        if (!self::hasPayloads()) {
            return;
        }

        $snapshotPath = ConfigProvider::get('snapshotPath');
        if (!is_string($snapshotPath)) {
            throw new ConfigException('Snapshot path is not a string');
        }

        ProcessManagement::startPercySnapshotServer();
        $client = ClientFactory::create();

        foreach (self::$payloads as $payload) {
            codecept_debug(sprintf('[Percy] Sending snapshot "%s"', $payload->getName()));

            $client->post($snapshotPath, $payload);
        }

        ProcessManagement::stopPercySnapshotServer();

        self::resetRequest();
    }

    /**
     * Reset payloads
     */
    public static function resetRequest(): void
    {
        self::$payloads = [];

        if (ConfigProvider::shouldCleanSnapshotStorage()) {
            SnapshotManagement::clean();
        }
    }
}
