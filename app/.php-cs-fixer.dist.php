<?php

declare(strict_types=1);

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
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
        'general_phpdoc_annotation_remove' => [
            'annotations' => ['expectedDeprecation'],
        ],
        'modernize_strpos'                 => true, // needs PHP 8+ or polyfill
        'native_constant_invocation'       => ['strict' => false], // strict:false to not remove `\` on low-end PHP versions for not-yet-known consts
        'no_useless_concat_operator'       => false, // TODO switch back on when the `src/Console/Application.php` no longer needs the concat
        'numeric_literal_separator'        => true,
        'binary_operator_spaces'           => ['default' => 'single_space', 'operators' => ['=' => 'align_single_space', '=>' => 'align_single_space']],
        'phpdoc_order'                     => [
            'order' => [
                'type',
                'template',
                'template-covariant',
                'template-extends',
                'extends',
                'implements',
                'property',
                'method',
                'param',
                'return',
                'var',
                'assert',
                'assert-if-false',
                'assert-if-true',
                'throws',
                'author',
                'see',
            ],
        ],
    ])
    ->setFinder(
        (new Finder())
            ->ignoreDotFiles(false)
            ->ignoreVCSIgnored(true)
            ->exclude(['dev-tools/phpstan', 'tests/Fixtures'])
            ->in(__DIR__)
            ->append([__DIR__.'/php-cs-fixer'])
    )
;
