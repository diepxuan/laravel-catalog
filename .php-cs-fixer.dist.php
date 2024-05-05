<?php

declare(strict_types=1);

/*
 * Copyright © 2019 Dxvn, Inc. All rights reserved.
 *
 * © Tran Ngoc Duc <ductn@diepxuan.com>
 *   Tran Ngoc Duc <caothu91@gmail.com>
 */

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PHP74Migration'                  => true,
        '@PHP74Migration:risky'            => true,
        '@PHPUnit100Migration:risky'       => true,
        '@PhpCsFixer'                      => true,
        '@PhpCsFixer:risky'                => true,
        'general_phpdoc_annotation_remove' => ['annotations' => ['expectedDeprecation']], // one should use PHPUnit built-in method instead
        'header_comment'                   => ['header' => <<<'EOF'
            Copyright © 2019 Dxvn, Inc. All rights reserved.

            © Tran Ngoc Duc <ductn@diepxuan.com>
              Tran Ngoc Duc <caothu91@gmail.com>
            EOF],
        'modernize_strpos'            => true, // needs PHP 8+ or polyfill
        'no_useless_concat_operator'  => false, // TODO switch back on when the `src/Console/Application.php` no longer needs the concat
        'numeric_literal_separator'   => true,
        'string_implicit_backslashes' => true, // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/pull/7786

        '@PSR2'              => true,
        'array_syntax'       => ['syntax' => 'short'],
        'concat_space'       => ['spacing' => 'one'],
        'include'            => true,
        'new_with_braces'    => true,
        'no_empty_statement' => true,
        // 'no_extra_consecutive_blank_lines' => true,
        'no_leading_import_slash'                     => true,
        'no_leading_namespace_whitespace'             => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        // 'multiline_whitespace_before_semicolons' => false,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_trailing_comma_in_singleline_array'      => true,
        'no_alias_language_construct_call'           => true,
        'assign_null_coalescing_to_coalesce_equal'   => true,
        'no_unused_imports'                          => true,
        'no_whitespace_in_blank_line'                => true,
        'object_operator_without_whitespace'         => true,
        'ordered_imports'                            => true,
        'standardize_not_equals'                     => true,
        'ternary_operator_spaces'                    => true,
        'binary_operator_spaces'                     => [
            'default'   => 'at_least_single_space',
            'operators' => [
                '=>' => 'align_single_space_minimal',
                '='  => 'align_single_space_minimal',
            ]],

        'operator_linebreak' => ['only_booleans' => true],
    ])
    ->setFinder(
        (new Finder())
            ->ignoreDotFiles(false)
            ->ignoreVCSIgnored(true)
            ->exclude(['dev-tools/phpstan', 'tests/Fixtures'])
            ->in(__DIR__)
    )
;
