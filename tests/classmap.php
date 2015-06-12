<?php
$classmap = require __DIR__ . '/../vendor/composer/autoload_classmap.php';
$classmap += [
    'Phantestic\\Tests\\TestCase\\TestCaseTest' => __DIR__ . '/TestCase/TestCaseTest.php',
    'Phantestic\\Tests\\TestHandler\\CliReporterTest' => __DIR__ . '/TestHandler/CliReporterTest.php',
];
return $classmap;
