<?php

namespace Phantestic\Tests\Handler;

use Evenement\EventEmitter;
use Phantestic\Handler\CliOutputHandler;
use Phantestic\Test\Test;
use Phantestic\Tests\Assertions;

class CliOutputHandlerTest
{
    use Assertions;

    protected $emitter;

    protected $handler;

    protected $noop;

    public function __construct()
    {
        $this->emitter = new EventEmitter;
        $this->handler = new CliOutputHandler;
        $this->handler->setEventEmitter($this->emitter);
        $this->noop = function () {
            // noop
        };
    }

    public function testBeforeTests()
    {
        ob_start();
        $this->emitter->emit('phantestic.tests.before');
        $this->assertSame("Phantestic by Matthew Turland\n\n", ob_get_clean());
    }

    public function testHandleFail()
    {
        ob_start();
        $case = new Test($this->noop, 'name');
        $this->emitter->emit('phantestic.test.failresult', [$case]);
        $this->assertSame('F', ob_get_clean());
    }

    public function testHandlePass()
    {
        ob_start();
        $case = new Test($this->noop, 'name');
        $this->emitter->emit('phantestic.test.passresult', [$case]);
        $this->assertSame('.', ob_get_clean());
    }

    public function testAfterTestsWithoutFailures()
    {
        ob_start();
        $this->emitter->emit('phantestic.tests.before');
        $case = new Test($this->noop, 'name');
        $this->emitter->emit('phantestic.test.passresult', [$case]);
        $this->emitter->emit('phantestic.tests.after');
        $pattern = "/^\\.\n\n"
            . "Tests: 1, Passed: 1, Failed: 0\n"
            . "Time: (?:[0-9.]+)s, Memory: (?:[0-9.]+)(?:[bKM])\n$/m";
        $this->assertRegExp($pattern, ob_get_clean());
    }

    public function testAfterTestsWithFailures()
    {
        $file = __DIR__ . '/_files/CliOutputHandlerTest_afterTestsWithFailures.php';
        $spec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
        ];
        $proc = proc_open('php ' . $file, $spec, $pipes);
        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        $exit = proc_close($proc);
        $pattern = "%^Phantestic by Matthew Turland\n\n"
            . "F\n\n"
            . "Tests: 1, Passed: 0, Failed: 1\n"
            . "Time: (?:[0-9.]+)s, Memory: (?:[0-9.]+)(?:[bKM])\n\n"
            . "Failures:\n"
            . "1\\) name\n"
            . "test failed\n"
            . "#0 {main}%m";
        $this->assertRegExp($pattern, $stdout);
        $this->assertSame(1, $exit);
    }
}
