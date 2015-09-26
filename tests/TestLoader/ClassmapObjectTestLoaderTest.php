<?php

namespace Phantestic\Tests\TestLoader;

use Evenement\EventEmitter;
use Phantestic\TestAssertions;

class ClassmapObjectTestLoaderTest
{
    use TestAssertions;

    public function testWithDefaults()
    {
        $loader = new ClassmapObjectTestLoaderSubclass;
        $this->testLoader($loader);
    }

    public function testWithOverrides()
    {
        $called = false;
        $callback = function($case, $class, $method) use (&$called) {
            $called = true;
            $this->assertInstanceOf('Phantestic\\TestCase\\TestCase', $case);
            $this->assertSame(__NAMESPACE__ . '\\PassingTest', $class);
            $this->assertSame('testPassingTestMethod', $method);
        };
        $filter = function($file, $class, $method) {
            return
                $file === __DIR__ . '/PassingTest.php'
                && $class === __NAMESPACE__ . '\\PassingTest'
                && $method === 'testPassingTestMethod';
        };
        $generator = function($class, $method) {
            $this->assertSame(__NAMESPACE__ . '\\PassingTest', $class);
            $this->assertSame('testPassingTestMethod', $method);
            return new \Phantestic\TestCase\TestCase(function(){}, 'foo');
        };
        $emitter = new EventEmitter;
        $emitter->on('phantestic.loader.loaded', $callback);

        $loader = new ClassmapObjectTestLoaderSubclass($emitter, $filter, $generator);
        $this->testLoader($loader);

        $this->assertTrue($called, 'Event callback was not called');
    }

    protected function testLoader(ClassmapObjectTestLoaderSubclass $loader)
    {
        $iterator = $loader->getIterator();
        $this->assertInstanceOf('\Traversable', $iterator);
        $cases = iterator_to_array($iterator);
        $this->assertCount(1, $cases);
        $case = reset($cases);
        $this->assertInstanceOf('\Phantestic\TestCase\TestCase', $case);
        $case->run();
        $this->assertInstanceOf('\Phantestic\TestResult\PassResult', $case->getResult());
    }
}
