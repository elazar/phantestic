<?php

namespace Phantestic\Tests\TestCase;

use Phantestic\TestCase\TestCase;
use Phantestic\TestResult\TestResult;
use Phantestic\Tests\TestAssertions;

class TestCaseTest
{
    use TestAssertions;

    public function testRunWithPassingTest()
    {
        $callback = function () {
            // noop
        };
        $case = new TestCase($callback, 'name');
        $case->run();
        $this->assertInstanceOf('\Phantestic\TestResult\PassResult', $case->getResult());
    }

    public function testRunWithFailingTest()
    {
        $callback = function () {
            throw new \RuntimeException('test failure');
        };
        $case = new TestCase($callback, 'name');
        $case->run();
        $this->assertInstanceOf('\Phantestic\TestResult\FailResult', $case->getResult());
    }

    public function testRunWithGenericTestResult()
    {
        $result = new TestResult('test result');
        $callback = function () use ($result) {
            throw $result;
        };
        $case = new TestCase($callback, 'name');
        $case->run();
        $this->assertSame($result, $case->getResult());
    }

    public function testGetName()
    {
        $callback = function () {
            // noop
        };
        $test = new TestCase($callback, 'foo');
        $this->assertSame('foo', $test->getName());
    }
}
