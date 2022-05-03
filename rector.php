<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::DEAD_CODE);
    $containerConfigurator->import(SetList::EARLY_RETURN);
    $containerConfigurator->import(SetList::CODE_QUALITY);
    $containerConfigurator->import(SetList::TYPE_DECLARATION);
    $containerConfigurator->import(SetList::TYPE_DECLARATION_STRICT);
    $containerConfigurator->import(SetList::PHP_74);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [__DIR__ . '/src']);
    $parameters->set(Option::SKIP, [
        RemoveUselessParamTagRector::class,
        RemoveUselessReturnTagRector::class,
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
