<?php

namespace Phantestic\Loader;

use Phantestic\Classmap\Builder\BuilderInterface as ClassmapBuilder;
use Phantestic\Iterator\MethodIterator;
use Phantestic\Test\Builder\MethodBuilder;
use Evenement\EventEmitterInterface;

class ClassmapObjectLoader implements \IteratorAggregate
{
    /**
     * @var \Phantestic\Classmap\Builder\BuilderInterface
     */
    protected $classmap;

    /**
     * @var \Evenement\EventEmitterInterface
     */
    protected $emitter;

    /**
     * @param \Phantestic\Classmap\Builder\BuilderInterface $classmap
     */
    public function __construct(
        ClassmapBuilder $classmap
    ) {
        $this->classmap = $classmap;
        $this->emitter = null;
    }

    /**
     * @param EventEmitterInterface $emitter
     */
    public function setEmitter(EventEmitterInterface $emitter)
    {
        $this->emitter = $emitter;
    }

    /**
     * @return \Phantestic\Iterator\MethodIterator
     */
    protected function getMethodIterator()
    {
        return new MethodIterator($this->classmap->getClassmap());
    }

    /**
     * @param \ReflectionMethod $method
     * @return \Phantestic\Test\TestInterface
     */
    protected function buildTest(\ReflectionMethod $method)
    {
        $builder = new MethodBuilder($method);
        return $builder->getTest();
    }

    /**
     * @return \Generator
     */
    public function getIterator()
    {
        foreach ($this->getMethodIterator() as $method) {
            $test = $this->buildTest($method);
            if ($this->emitter) {
                $this->emitter->emit('phantestic.loader.loaded', [$test, $method]);
            }
            yield $test;
        }
    }
}
