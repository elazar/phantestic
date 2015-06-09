<?php

namespace Phantestic\TestHandler;

use Evenement\EventEmitterInterface;
use Phantestic\TestHandler\TestHandlerInterface;
use Phantestic\TestCase\TestCaseInterface;

class CliReporter implements TestHandlerInterface
{
    /**
     * @var \Phantestic\TestCase\TestCaseInterface[]
     */
    protected $failures;

    public function __construct()
    {
        $this->failures = [];
    }

    public function setEventEmitter(EventEmitterInterface $emitter)
    {
        $emitter->on('phantestic.test.failresult', [$this, 'handleFail']);
        $emitter->on('phantestic.test.passresult', [$this, 'handlePass']);
        $emitter->on('phantestic.tests.after', [$this, 'printSummary']);
    }

    public function handleFail(TestCaseInterface $case)
    {
        echo 'F';

        $this->failures[] = $case;
    }

    public function handlePass(TestCaseInterface $case)
    {
        echo '.';
    }

    public function printSummary()
    {
        echo PHP_EOL, PHP_EOL;

        if (empty($this->failures)) {
            echo 'Tests passed!', PHP_EOL;
        } else {
            echo 'FAILURES:', PHP_EOL;
            foreach ($this->failures as $case) {
                $exception = $case->getResult()->getException();
                echo $e->getMessage(), PHP_EOL;
                echo $e->getTraceAsString(), PHP_EOL, PHP_EOL;
            }
            register_shutdown_function([$this, 'returnExitStatus']);
        }
    }

    public function returnExitStatus()
    {
        exit(1);
    }
}
