<?php

define ('TEST_BASE_DIR', __DIR__);
define('BASE_DIR', TEST_BASE_DIR.'/');
system('rm -rf ' . escapeshellarg(TEST_BASE_DIR.'/data'));
$baseDir = dirname(__DIR__.'/../../');
if (!file_exists($autoloadFile = __DIR__.'/../vendor/autoload.php')) {
    throw new \LogicException('Could not find autoload.php in vendor/. Did you run "composer install --dev"?');
}

$loader = require $autoloadFile;

$loader->add('Itkg\\Profiler', array($baseDir.'/src/', $baseDir.'/tests/'));
$loader->register();
