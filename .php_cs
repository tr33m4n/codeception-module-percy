<?php

use AdamWojs\PhpCsFixerPhpdocForceFQCN\Fixer\Phpdoc\ForceFQCNFixer;

$finder = PhpCsFixer\Finder::create()->in(__DIR__ . '/src');

return PhpCsFixer\Config::create()
    ->registerCustomFixers([new ForceFQCNFixer()])
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'cast_spaces' => true,
        'trailing_comma_in_multiline_array' => false,
        'no_unused_imports' => true,
        'AdamWojs/phpdoc_force_fqcn_fixer' => true
    ])
    ->setFinder($finder);
