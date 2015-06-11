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

    /**
     * @var \Phantestic\TestCase\TestCaseInterface[]
     */
    protected $passes;

    /**
     * @var float
     */
    protected $time;

    public function __construct()
    {
        $this->failures = [];
        $this->passes = [];
    }

    public function setEventEmitter(EventEmitterInterface $emitter)
    {
        $emitter->on('phantestic.test.failresult', [$this, 'handleFail']);
        $emitter->on('phantestic.test.passresult', [$this, 'handlePass']);
        $emitter->on('phantestic.tests.before', [$this, 'startMetrics']);
        $emitter->on('phantestic.tests.after', [$this, 'printSummary']);
    }

    public function startMetrics()
    {
        $this->time = microtime(true);
    }

    public function handleFail(TestCaseInterface $case)
    {
        echo 'F';

        $this->failures[] = $case;
    }

    public function handlePass(TestCaseInterface $case)
    {
        echo '.';

        $this->passes[] = $case;
    }

    public function printSummary()
    {
        $time = $this->formatTime(microtime(true) - $this->time);
        $memory = $this->formatMemory(memory_get_peak_usage(true));
        $passes = count($this->passes);
        $failures = count($this->failures);
        $total = $failures + $passes;

        echo PHP_EOL, PHP_EOL;
        echo 'Tests: ', $total, ', Passed: ', $passes, ', Failed: ', $failures, PHP_EOL;
        echo 'Time: ', $time, ', Memory: ', $memory, PHP_EOL, PHP_EOL;

        if ($failures) {
            echo 'Failures:', PHP_EOL;
            $no = 0;
            foreach ($this->failures as $case) {
                $exception = $case->getResult()->getException();
                echo ++$no, ') ', $e->getMessage(), PHP_EOL;
                echo $e->getTraceAsString(), PHP_EOL, PHP_EOL;
            }
            register_shutdown_function([$this, 'returnExitStatus']);
        }
    }

    public function returnExitStatus()
    {
        exit(1);
    }

    protected function formatTime($time)
    {
        $formatted = [];
        if ($time > 3600) {
            $h = floor($time / 3600);
            $formatted[] = $h . 'h';
            $time %= 3600;
        }
        if ($time > 60) {
            $m = floor($time / 60);
            $formatted[] = $m . 'm';
            $time %= 60;
        }
        $formatted[] = number_format($time, 2) . 's';
        return implode(' ', $formatted);
    }

    protected function formatMemory($memory)
    {
        if ($memory >= 1073741824) {
            $memory /= 1073741824;
            $unit = 'G';
        } elseif ($memory >= 1048576) {
            $memory /= 1048576;
            $unit = 'M';
        } elseif ($memory >= 1024) {
            $memory /= 1024;
            $unit = 'K';
        } else {
            $unit = 'b';
        }
        if (!is_int($memory)) {
            $memory = number_format($memory, 2);
        }
        $formatted = $memory . $unit;
        return $formatted;
    }
}
