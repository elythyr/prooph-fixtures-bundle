<?php

$config = new Prooph\CS\Config\Prooph();
$config->getFinder()
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('tests/cache');

$cacheDir = getenv('TRAVIS') ? getenv('HOME') . '/.php-cs-fixer' : __DIR__;

$config->setCacheFile($cacheDir . '/.php_cs.cache');

return $config;
