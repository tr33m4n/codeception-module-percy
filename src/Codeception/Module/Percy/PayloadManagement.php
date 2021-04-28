<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exchange\Adapter\CurlAdapter;
use Codeception\Module\Percy\Exchange\Client;

/**
 * Class PayloadManagement
 *
 * @package Codeception\Module\Percy
 */
class PayloadManagement
{
    /**
     * @var \Codeception\Module\Percy\Payload[]
     */
    private static $payloads = [];

    /**
     * Add a payload
     *
     * @param \Codeception\Module\Percy\Payload $payload
     */
    public static function add(Payload $payload): void
    {
        self::$payloads[] = $payload;
    }

    /**
     * Send payloads to Percy
     *
     * @throws \Codeception\Module\Percy\Exception\AdapterException
     */
    public static function send(): void
    {
        if (empty(self::$payloads)) {
            return;
        }

        ProcessManagement::startPercyAgent();
        $client = Client::create(CurlAdapter::create(ConfigProvider::get('agentEndpoint')));

        foreach (self::$payloads as $payload) {
            codecept_debug(sprintf('[Percy] Sending snapshot "%s"', $payload->getName()));

            $client->post(ConfigProvider::get('agentSnapshotPath'), $payload);
        }

        ProcessManagement::stopPercyAgent();

        self::clear();
    }

    /**
     * Clear payloads
     */
    public static function clear(): void
    {
        self::$payloads = [];

        if (ConfigProvider::get('cleanSnapshotStorage')) {
            SnapshotManagement::clean();
        }
    }
}
