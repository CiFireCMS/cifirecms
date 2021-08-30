<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/l-app',
    ])
    ->exclude('views')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@PSR2' => true
    ])
    ->setFinder($finder);
