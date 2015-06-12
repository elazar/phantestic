<?php

namespace Phantestic\Tests\TestCase;

use Phantestic\TestAssertions;
use Phantestic\TestCase\TestCase;
use Phantestic\TestResult\TestResult;

class TestCaseTest
{
    use TestAssertions;

    public function testRunWithPassingTest()
    {
        $callback = function() { };
        $case = new TestCase($callback);
        $case->run();
        $this->assertInstanceOf('\Phantestic\TestResult\PassResult', $case->getResult());
    }

    public function testRunWithFailingTest()
    {
        $callback = function() { throw new \RuntimeException('test failure'); };
        $case = new TestCase($callback);
        $case->run();
        $this->assertInstanceOf('\Phantestic\TestResult\FailResult', $case->getResult());
    }

    public function testRunWithGenericTestResult()
    {
        $result = new TestResult('test result');
        $callback = function() use ($result) { throw $result; };
        $case = new TestCase($callback);
        $case->run();
        $this->assertSame($result, $case->getResult());
    }
}
