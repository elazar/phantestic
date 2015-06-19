<?php

require __DIR__ . '/../../../vendor/autoload.php';

use Evenement\EventEmitter;
use Phantestic\TestCase\TestCase;
use Phantestic\TestHandler\CliOutputTestHandler;

$emitter = new EventEmitter;
$handler = new CliOutputTestHandler;
$handler->setEventEmitter($emitter);

$emitter->emit('phantestic.tests.before');

$exception = new \Exception('test failed');
$case = new TestCase(
    function() use ($exception) {
        throw $exception;
    },
    'name'
);
$case->run();

$emitter->emit('phantestic.test.failresult', [$case]);
$emitter->emit('phantestic.tests.after');
