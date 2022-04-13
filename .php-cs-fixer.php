<?php

use AdamWojs\PhpCsFixerPhpdocForceFQCN\Fixer\Phpdoc\ForceFQCNFixer;

$finder = PhpCsFixer\Finder::create()->in(__DIR__ . '/src');

return (new PhpCsFixer\Config)->registerCustomFixers([new ForceFQCNFixer()])
    ->setRules([
        '@PSR2' => true,
        'blank_line_after_opening_tag' => true,
        'braces' => ['allow_single_line_anonymous_class_with_empty_body' => true],
        'compact_nullable_typehint' => true,
        'declare_equal_normalize' => true,
        'lowercase_cast' => true,
        'lowercase_static_reference' => true,
        'new_with_braces' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_leading_import_slash' => true,
        'no_whitespace_in_blank_line' => true,
        'ordered_class_elements' => ['order' => ['use_trait']],
        'ordered_imports' => ['imports_order' => ['class', 'function', 'const'], 'sort_algorithm' => 'none'],
        'return_type_declaration' => true,
        'short_scalar_cast' => true,
        'single_blank_line_before_namespace' => true,
        'single_trait_insert_per_statement' => true,
        'ternary_operator_spaces' => true,
        'visibility_required' => ['elements' => ['const', 'method', 'property']],
        'array_syntax' => ['syntax' => 'short'],
        'cast_spaces' => true,
        'trailing_comma_in_multiline' => false,
        'no_unused_imports' => true,
        'AdamWojs/phpdoc_force_fqcn_fixer' => true
    ])
    ->setFinder($finder);
