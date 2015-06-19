<?php

require __DIR__ . '/../../../vendor/autoload.php';

use Evenement\EventEmitter;
use Phantestic\TestCase\TestCase;
use Phantestic\TestHandler\CliOutputTestHandler;

$emitter = new EventEmitter;
$handler = new CliOutputTestHandler;
$handler->setEventEmitter($emitter);

$emitter->emit('phantestic.tests.before');

$e = new \Exception('test failed');
$case = new TestCase(function() use ($e) { throw $e; }, 'name');
$case->run();

$emitter->emit('phantestic.test.failresult', [$case]);
$emitter->emit('phantestic.tests.after');
