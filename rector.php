<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\MemoryCacheStorage;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(SetList::DEAD_CODE);
    $rectorConfig->import(SetList::EARLY_RETURN);
    $rectorConfig->import(SetList::CODE_QUALITY);
    $rectorConfig->import(SetList::TYPE_DECLARATION);
    $rectorConfig->import(LevelSetList::UP_TO_PHP_81);

    $rectorConfig->cacheClass(MemoryCacheStorage::class);

    $rectorConfig->paths([__DIR__ . '/src']);
    $rectorConfig->skip([
        MyCLabsMethodCallToEnumConstRector::class
    ]);
};
