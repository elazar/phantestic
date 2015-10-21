<?php

namespace Phantestic\Tests\Test;

use Phantestic\Result\Result;
use Phantestic\Test\Test;
use Phantestic\Tests\Assertions;

class TestTest
{
    use Assertions;

    public function testRunWithPassingTest()
    {
        $callback = function () {
            // noop
        };
        $case = new Test($callback, 'name');
        $case->run();
        $this->assertInstanceOf('\Phantestic\Result\PassResult', $case->getResult());
    }

    public function testRunWithFailingTest()
    {
        $callback = function () {
            throw new \RuntimeException('test failure');
        };
        $case = new Test($callback, 'name');
        $case->run();
        $this->assertInstanceOf('\Phantestic\Result\FailResult', $case->getResult());
    }

    public function testRunWithGenericTestResult()
    {
        $result = new Result('test result');
        $callback = function () use ($result) {
            throw $result;
        };
        $case = new Test($callback, 'name');
        $case->run();
        $this->assertSame($result, $case->getResult());
    }

    public function testGetName()
    {
        $callback = function () {
            // noop
        };
        $test = new Test($callback, 'foo');
        $this->assertSame('foo', $test->getName());
    }
}
