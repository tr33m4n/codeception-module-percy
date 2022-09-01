<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

final class Definitions
{
    public const NAMESPACE = 'Percy';

    public const PACKAGE_NAME = 'tr33m4n/codeception-module-percy';

    public const DEFAULT_CONFIG = [
        'serializeConfig' => [
            'enableJavaScript' => true
        ],
        'snapshotConfig' => [
            'widths' => [
                375,
                1280
            ],
            'minHeight' => 1024
        ],
        'collectOnly' => false,
        'snapshotServerTimeout' => 30,
        'snapshotServerPort' => 5338,
        'snapshotServerDebug' => false,
        'snapshotPathTemplate' => null,
        'throwOnAdapterError' => true,
        'instanceId' => null
    ];
}
