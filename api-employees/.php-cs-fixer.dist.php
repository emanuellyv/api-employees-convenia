<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__.'/app')
    ->in(__DIR__.'/routes')
    ->in(__DIR__.'/database')
    ->in(__DIR__.'/config')
    ->in(__DIR__.'/tests')
    ->name('*.php')
    ->notName('_ide_helper*.php')
    ->notPath('storage')
    ->notPath('bootstrap/cache')
    ->exclude('vendor');

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => true,
        'no_unused_imports' => true,
        'single_quote' => true,
        'binary_operator_spaces' => [
            'default' => 'single_space',
            'operators' => ['=>' => 'align_single_space'],
        ],
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_order' => true,
        'not_operator_with_successor_space' => true,
        'not_operator_with_space' => false,
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],
        'no_superfluous_phpdoc_tags' => true,
        'blank_line_before_statement' => [
            'statements' => ['return', 'if', 'foreach', 'for', 'while', 'switch', 'try'],
        ],
    ])
    ->setFinder($finder);
