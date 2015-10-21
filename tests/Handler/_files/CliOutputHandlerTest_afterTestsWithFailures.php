<?php

require __DIR__ . '/../../../vendor/autoload.php';

use Evenement\EventEmitter;
use Phantestic\Handler\CliOutputHandler;
use Phantestic\Test\Test;

$emitter = new EventEmitter;
$handler = new CliOutputHandler;
$handler->setEventEmitter($emitter);

$emitter->emit('phantestic.tests.before');

$exception = new \Exception('test failed');
$case = new Test(
    function () use ($exception) {
        throw $exception;
    },
    'name'
);
$case->run();

$emitter->emit('phantestic.test.failresult', [$case]);
$emitter->emit('phantestic.tests.after');
