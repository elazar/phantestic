#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';
$classmap_path = __DIR__ . '/../vendor/composer/autoload_classmap.php';
$loader = new \Phantestic\Loader\ClassmapFileObjectLoader($classmap_path);
$handlers = [ new \Phantestic\Handler\CliOutputHandler ];
$runner = new \Phantestic\Runner\LocalRunner($loader, $handlers);
$runner->run();
