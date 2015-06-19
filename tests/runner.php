#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';
$classmap_path = __DIR__ . '/../vendor/composer/autoload_classmap.php';
$loader = new \Phantestic\TestLoader\ClassmapFileObjectTestLoader($classmap_path);
$handlers = [ new \Phantestic\TestHandler\CliReporter ];
$runner = new \Phantestic\TestRunner\LocalTestRunner($loader, $handlers);
$runner->run();
