<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$config = new Config();

$finder = Finder::create()
    ->exclude(['vendor'])
    ->in(getcwd())
    ->name('*.php')
    ->name('*.phpt')
    ->notName('*.blade.php')
    ->notName('_ide_helper.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
;

$rules = [
    '@PSR12' => true,
    '@Symfony' => true,
    'concat_space' => ['spacing' => 'one'],
    'phpdoc_no_alias_tag' => false,
    'phpdoc_align' => [
        'align' => 'vertical',
        'tags' => [
            'method',
            'param',
            'property',
            'property-read',
            'property-write',
            'return',
            'throws',
            'type',
            'var',
        ],
    ],
];

return $config->setRules($rules)
    ->setFinder($finder)
    ->setUsingCache(false)
;
