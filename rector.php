<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(SetList::DEAD_CODE);
    $rectorConfig->import(SetList::EARLY_RETURN);
    $rectorConfig->import(SetList::CODE_QUALITY);
    $rectorConfig->import(SetList::TYPE_DECLARATION);
    $rectorConfig->import(SetList::TYPE_DECLARATION_STRICT);
    $rectorConfig->import(SetList::PHP_74);

    $rectorConfig->paths([__DIR__ . '/src']);
    $rectorConfig->skip([
        ReturnTypeDeclarationRector::class => [
            __DIR__ . '/src/Codeception/Module/Percy/Exchange/Adapter/CurlAdapter.php',
            __DIR__ . '/src/Codeception/Module/Percy/RequestManagement.php',
            __DIR__ . '/src/Codeception/Module/Percy/Snapshot.php'
        ],
        TypedPropertyRector::class => [
            __DIR__ . '/src/Codeception/Module/Percy/Exchange/Adapter/CurlAdapter.php'
        ],
        TypedPropertyFromAssignsRector::class => [
            __DIR__ . '/src/Codeception/Module/Percy/Exchange/Adapter/CurlAdapter.php'
        ],
        TypedPropertyFromStrictConstructorRector::class => [
            __DIR__ . '/src/Codeception/Module/Percy/Exchange/Adapter/CurlAdapter.php'
        ]
    ]);
};
