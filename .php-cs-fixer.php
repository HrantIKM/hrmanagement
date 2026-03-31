<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__)
    ->exclude('vendor')
    ->notName('*.blade.php');

return (new Config())
    ->setRules([
        '@PhpCsFixer' => true,
        'array_syntax' => ['syntax' => 'short'], // Use short array syntax
        'yoda_style' => false,
        'php_unit_method_casing' => false,
        'not_operator_with_successor_space' => false,
        'explicit_string_variable' => false,
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'fully_qualified_strict_types' => true,
        'global_namespace_import' => true,
        'blank_line_after_namespace' => false,
        'ordered_class_elements' => false,
        'cast_spaces' => false,
        'trailing_comma_in_multiline' => false,
        'single_line_empty_body' => false,
        'function_declaration' => [
            'closure_function_spacing' => 'one',
            'closure_fn_spacing' => 'none',
        ],
        'class_attributes_separation' => ['elements' => ['method' => 'one']],
        'concat_space' => ['spacing' => 'one'],
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],

        // Add or disable other rules as needed
    ])
    ->setFinder($finder);
