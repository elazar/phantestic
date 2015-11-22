<?php

namespace Phantestic\Iterator;

class MethodIterator implements \IteratorAggregate
{
    /**
     * @var string[]
     */
    protected $classes;

    /**
     * @param string[] $classes
     */
    public function __construct(array $classes)
    {
        $this->classes = $classes;
    }

    /**
     * @return \Generator
     */
    public function getIterator()
    {
        foreach ($this->classes as $class) {
            $reflector = new \ReflectionClass($class);
            foreach ($reflector->getMethods() as $method) {
                yield $method;
            }
        }
    }
}
