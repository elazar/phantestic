<?php

namespace Phantestic\Tests\Loader;

use Evenement\EventEmitter;
use Phantestic\Tests\Assertions;

class ClassmapObjectLoaderTest
{
    use Assertions;

    public function testWithDefaults()
    {
        $loader = new ClassmapObjectLoaderSubclass;
        $this->testLoader($loader);
    }

    public function testWithOverrides()
    {
        $called = false;
        $callback = function ($case, $class, $method) use (&$called) {
            $called = true;
            $this->assertInstanceOf('Phantestic\\Test\\Test', $case);
            $this->assertSame(__NAMESPACE__ . '\\PassingTest', $class);
            $this->assertSame('testPassingTestMethod', $method);
        };
        $filter = function ($file, $class, $method) {
            return
                $file === __DIR__ . '/PassingTest.php'
                && $class === __NAMESPACE__ . '\\PassingTest'
                && $method === 'testPassingTestMethod';
        };
        $generator = function ($class, $method) {
            $this->assertSame(__NAMESPACE__ . '\\PassingTest', $class);
            $this->assertSame('testPassingTestMethod', $method);
            $callback = function () {
                // noop
            };
            return new \Phantestic\Test\Test($callback, 'foo');
        };
        $emitter = new EventEmitter;
        $emitter->on('phantestic.loader.loaded', $callback);

        $loader = new ClassmapObjectLoaderSubclass($emitter, $filter, $generator);
        $this->testLoader($loader);

        $this->assertTrue($called, 'Event callback was not called');
    }

    protected function testLoader(ClassmapObjectLoaderSubclass $loader)
    {
        $iterator = $loader->getIterator();
        $this->assertInstanceOf('\Traversable', $iterator);
        $cases = iterator_to_array($iterator);
        $this->assertCount(1, $cases);
        $case = reset($cases);
        $this->assertInstanceOf('\Phantestic\Test\Test', $case);
        $case->run();
        $this->assertInstanceOf('\Phantestic\Result\PassResult', $case->getResult());
    }
}
